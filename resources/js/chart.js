import ApexCharts from 'apexcharts'

const chartPalette = [
	'#004179',
	'#f3c404',
	'#2f8f9d',
	'#ef6f6c',
	'#6c63ff',
	'#f59e0b',
	'#10b981',
	'#8b5cf6',
	'#ec4899',
	'#0ea5e9',
]

const chartInstances = new WeakMap()

function resolveContainer(con) {
	if (typeof con === 'string') {
		return document.querySelector(con)
	}

	if (typeof Element !== 'undefined' && con instanceof Element) {
		return con
	}

	return null
}

function normalizeData(data) {
	let parsedData = data

	if (typeof parsedData === 'string') {
		try {
			parsedData = JSON.parse(parsedData)
		} catch (error) {
			parsedData = []
		}
	}

	if (!Array.isArray(parsedData)) {
		return []
	}

	return parsedData.map((item) => {
		const label = String(item?.label ?? '').trim()

		return {
			label: label || 'Option',
			value: Number(item?.value ?? 0) || 0,
		}
	})
}

function getColors(count) {
	return Array.from({ length: count }, (_, index) => chartPalette[index % chartPalette.length])
}

function destroyChart(container) {
	const existingChart = chartInstances.get(container)

	if (existingChart) {
		existingChart.destroy()
		chartInstances.delete(container)
	}
}

function renderEmptyState(container) {
	container.innerHTML = `
		<div class="flex min-h-80 items-center justify-center rounded-xl border border-dashed border-gray-200 bg-white text-sm text-gray-400">
			No responses yet for this question.
		</div>
	`
}

function renderChart(type, data, con) {
	const container = resolveContainer(con)

	if (!container) {
		return null
	}

	const chartData = normalizeData(data)
	const labels = chartData.map((item) => item.label)
	const seriesData = chartData.map((item) => item.value)
	const barSeriesData = chartData.map((item) => ({
		x: item.label,
		y: item.value,
	}))
	const totalResponses = seriesData.reduce((sum, value) => sum + value, 0)

	destroyChart(container)
	container.innerHTML = ''

	if (!chartData.length || totalResponses === 0) {
		renderEmptyState(container)
		return null
	}

	const colors = getColors(chartData.length)
	const baseConfig = {
		chart: {
			fontFamily: 'Instrument Sans, sans-serif',
			height: type === 'pie' ? 340 : 320,
			parentHeightOffset: 0,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false,
			},
		},
		colors,
		dataLabels: {
			enabled: true,
		},
		noData: {
			text: 'No chart data available',
		},
		states: {
			hover: {
				filter: {
					type: 'darken',
					value: 0.1,
				},
			},
		},
	}

	const options = type === 'pie'
		? {
			...baseConfig,
			chart: {
				...baseConfig.chart,
				type: 'pie',
			},
			labels,
			legend: {
				position: 'bottom',
				horizontalAlign: 'center',
				fontSize: '13px',
				markers: {
					width: 10,
					height: 10,
					radius: 999,
				},
			},
			series: seriesData,
			stroke: {
				width: 2,
				colors: ['#ffffff'],
			},
			tooltip: {
				y: {
					formatter: (value) => `${value} response${value === 1 ? '' : 's'}`,
				},
			},
			plotOptions: {
				pie: {
					expandOnClick: false,
				},
			},
			dataLabels: {
				enabled: true,
				formatter: (_, opts) => String(opts.w.config.series[opts.seriesIndex] ?? 0),
				style: {
					fontSize: '12px',
					fontWeight: 600,
				},
			},
		}
		: {
			...baseConfig,
			chart: {
				...baseConfig.chart,
				type: 'bar',
			},
			series: [
				{
					name: 'Responses',
					data: barSeriesData,
				},
			],
			plotOptions: {
				bar: {
					horizontal: true,
					distributed: true,
					borderRadius: 8,
					barHeight: '60%',
					dataLabels: {
						position: 'top',
					},
				},
			},
			xaxis: {
				labels: {
					style: {
						colors: '#6b7280',
					},
				},
				title: {
					text: 'Responses',
					style: {
						color: '#6b7280',
						fontWeight: 500,
					},
				},
				axisBorder: {
					color: '#e5e7eb',
				},
				axisTicks: {
					color: '#e5e7eb',
				},
			},
			yaxis: {
				min: 0,
				labels: {
					style: {
						colors: '#6b7280',
					},
				},
			},
			grid: {
				borderColor: '#e5e7eb',
				strokeDashArray: 4,
				padding: {
					left: 8,
					right: 12,
				},
			},
			tooltip: {
				y: {
					formatter: (value) => `${value} response${value === 1 ? '' : 's'}`,
				},
			},
			dataLabels: {
				enabled: true,
				offsetX: 8,
				formatter: (value) => String(value),
				style: {
					colors: ['#1f2937'],
					fontSize: '12px',
					fontWeight: 600,
				},
			},
		}

	const chart = new ApexCharts(container, options)

	chartInstances.set(container, chart)
	chart.render()

	return chart
}

function showPieChart(data, con) {
	return renderChart('pie', data, con)
}

function showBarChart(data, con) {
	return renderChart('bar', data, con)
}

function updateToggleButton(button, isActive) {
	button.setAttribute('aria-pressed', isActive ? 'true' : 'false')
	button.style.backgroundColor = isActive ? '#004179' : '#e2e8f0'
	button.style.color = isActive ? '#ffffff' : '#475569'
	button.style.boxShadow = isActive ? '0 1px 2px rgba(0, 0, 0, 0.08)' : 'none'
}

function renderSurveyChart(wrapper, type) {
	const container = wrapper.querySelector('[data-chart-container]')

	if (!container) {
		return null
	}

	const chartData = wrapper.dataset.chartData ?? '[]'

	if (type === 'pie') {
		showPieChart(chartData, container)
	} else {
		showBarChart(chartData, container)
	}

	wrapper.querySelectorAll('[data-chart-type]').forEach((button) => {
		updateToggleButton(button, button.dataset.chartType === type)
	})

	wrapper.dataset.chartCurrent = type

	return container
}

function toggleSurveyChart(button, type) {
	const wrapper = button?.closest('[data-survey-chart]')

	if (!wrapper) {
		return null
	}

	return renderSurveyChart(wrapper, type || 'bar')
}

function initSurveyCharts() {
	document.querySelectorAll('[data-survey-chart]').forEach((wrapper) => {
		const defaultType = wrapper.dataset.chartDefault ?? 'bar'

		renderSurveyChart(wrapper, defaultType)
	})
}

if (typeof window !== 'undefined') {
	window.showPieChart = showPieChart
	window.showBarChart = showBarChart
	window.toggleSurveyChart = toggleSurveyChart
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initSurveyCharts)
} else {
	initSurveyCharts()
}