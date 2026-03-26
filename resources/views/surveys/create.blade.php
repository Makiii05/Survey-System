<x-layout title="Create Survey - SurveyEase">
    <div class="px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-3xl font-bold mb-6" style="color: #004179;">Create Survey</h1>

        <form method="POST" action="{{ route('surveys.store') }}" id="surveyForm">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="Enter survey title">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="Describe your survey (optional)">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-6 mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_public" value="1" checked
                        class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm text-gray-700">Public</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="allow_multiple" value="1"
                        class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm text-gray-700">Allow multiple responses</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="requires_login" value="1"
                        class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm text-gray-700">Require login</span>
                </label>
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold" style="color: #004179;">Sections</h2>
                    <div class="flex gap-3">
                        <button type="button" onclick="addDemographicInfo()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition hover:opacity-90 cursor-pointer bg-gray-300 hover:bg-gray-400">
                            Require Demographic
                        </button>
                        <button type="button" onclick="openSectionModal()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition hover:opacity-90 cursor-pointer"
                            style="background-color: #f3c404; color: #004179;">
                            Add Section
                        </button>
                    </div>
                </div>
                <div id="sectionsList" class="space-y-4">
                </div>
                <p id="noSections" class="text-gray-400 text-sm py-8 text-center border border-dashed border-gray-300 rounded-lg">
                    No sections added yet. Click "Add Section" to get started.
                </p>
            </div>

            <input type="hidden" name="sections_json" id="sectionsJson">

            <button type="submit"
                class="px-8 py-2.5 rounded-lg text-white font-semibold transition hover:opacity-90 cursor-pointer"
                style="background-color: #004179;">
                Create Survey
            </button>
            <a href="{{ route('surveys.my') }}" class="ml-4 text-sm text-gray-500 hover:text-gray-700">&larr; Back to My Surveys</a>
        </form>
    </div>

    <div id="sectionModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 id="sectionModalTitle" class="text-lg font-semibold" style="color: #004179;">Add Section</h3>
                <button type="button" onclick="closeSectionModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer text-xl">&times;</button>
            </div>

            <div class="mb-4">
                <label for="modalSectionTitle" class="block text-sm font-medium text-gray-700 mb-1">Section Title</label>
                <input type="text" id="modalSectionTitle"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="Enter section title">
            </div>

            <div class="mb-6">
                <label for="modalSectionDescription" class="block text-sm font-medium text-gray-700 mb-1">Section Description</label>
                <textarea id="modalSectionDescription" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="Describe this section (optional)"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeSectionModal()"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 cursor-pointer">
                    Cancel
                </button>
                <button type="button" onclick="saveSection()"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 cursor-pointer"
                    style="background-color: #004179;">
                    <span id="sectionModalSaveLabel">Add Section</span>
                </button>
            </div>
        </div>
    </div>

    <div id="questionModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold" style="color: #004179;">Add Question</h3>
                <button type="button" onclick="closeQuestionModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer text-xl">&times;</button>
            </div>

            <div class="mb-4">
                <label for="modalQuestionText" class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                <input type="text" id="modalQuestionText"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="Enter your question">
            </div>

            <div class="mb-4">
                <label for="modalQuestionType" class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                <select id="modalQuestionType" onchange="toggleOptionsField()"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent">
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                    <option value="radio">Radio (Single Choice)</option>
                    <option value="checkbox">Checkbox (Multiple Choice)</option>
                    <option value="dropdown">Dropdown</option>
                    <option value="date">Date</option>
                    <option value="time">Time</option>
                    <option value="number">Number</option>
                    <option value="email">Email</option>
                    <option value="scale">Scale (1-10)</option>
                </select>
            </div>

            <div id="optionsField" class="mb-4 hidden">
                <label for="modalOptions" class="block text-sm font-medium text-gray-700 mb-1">Options (one per line)</label>
                <textarea id="modalOptions" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:border-transparent"
                    placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="modalRequired" class="w-4 h-4 rounded border-gray-300">
                    <span class="text-sm text-gray-700">Required</span>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeQuestionModal()"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 cursor-pointer">
                    Cancel
                </button>
                <button type="button" onclick="saveQuestion()"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 cursor-pointer"
                    style="background-color: #004179;">
                    <span id="modalSaveLabel">Add Question</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let sections = [];
        let editingSectionIndex = -1;
        let currentQuestionSectionIndex = -1;
        let editingQuestionIndex = -1;

        const typesWithOptions = ['radio', 'checkbox', 'dropdown'];
        const typeLabels = {
            text: 'Text', textarea: 'Textarea', radio: 'Radio', checkbox: 'Checkbox',
            dropdown: 'Dropdown', date: 'Date', time: 'Time', number: 'Number', email: 'Email', scale: 'Scale'
        };

        function openSectionModal(index = -1) {
            editingSectionIndex = index;
            const titleInput = document.getElementById('modalSectionTitle');
            const descriptionInput = document.getElementById('modalSectionDescription');

            if (index >= 0) {
                const section = sections[index];
                document.getElementById('sectionModalTitle').textContent = 'Edit Section';
                document.getElementById('sectionModalSaveLabel').textContent = 'Save Changes';
                titleInput.value = section.title;
                descriptionInput.value = section.description || '';
            } else {
                document.getElementById('sectionModalTitle').textContent = 'Add Section';
                document.getElementById('sectionModalSaveLabel').textContent = 'Add Section';
                titleInput.value = '';
                descriptionInput.value = '';
            }

            const modal = document.getElementById('sectionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeSectionModal() {
            const modal = document.getElementById('sectionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            editingSectionIndex = -1;
        }

        function saveSection() {
            const title = document.getElementById('modalSectionTitle').value.trim();
            const description = document.getElementById('modalSectionDescription').value.trim();

            if (!title) {
                alert('Please enter a section title.');
                return;
            }

            if (editingSectionIndex >= 0) {
                sections[editingSectionIndex].title = title;
                sections[editingSectionIndex].description = description;
            } else {
                sections.push({ title, description, questions: [] });
            }

            renderSections();
            closeSectionModal();
        }

        function removeSection(index) {
            sections.splice(index, 1);
            renderSections();
        }

        function updateSectionField(index, field, value) {
            sections[index][field] = value;
            updateSerializedSections();
        }

        function openQuestionModal(sectionIndex, questionIndex = -1) {
            currentQuestionSectionIndex = sectionIndex;
            editingQuestionIndex = questionIndex;

            document.getElementById('modalTitle').textContent = 'Add Question';
            document.getElementById('modalSaveLabel').textContent = 'Add Question';
            document.getElementById('modalQuestionText').value = '';
            document.getElementById('modalQuestionType').value = 'text';
            document.getElementById('modalOptions').value = '';
            document.getElementById('modalRequired').checked = false;

            if (questionIndex >= 0) {
                const question = sections[sectionIndex].questions[questionIndex];
                document.getElementById('modalTitle').textContent = 'Edit Question';
                document.getElementById('modalSaveLabel').textContent = 'Save Changes';
                document.getElementById('modalQuestionText').value = question.text;
                document.getElementById('modalQuestionType').value = question.type;
                document.getElementById('modalOptions').value = question.options.join('\n');
                document.getElementById('modalRequired').checked = question.required;
            }

            toggleOptionsField();
            const modal = document.getElementById('questionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeQuestionModal() {
            const modal = document.getElementById('questionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            editingQuestionIndex = -1;
            currentQuestionSectionIndex = -1;
        }

        function toggleOptionsField() {
            const type = document.getElementById('modalQuestionType').value;
            const field = document.getElementById('optionsField');
            if (typesWithOptions.includes(type)) {
                field.classList.remove('hidden');
            } else {
                field.classList.add('hidden');
            }
        }

        function addDemographicInfo() {
            const demographicQuestions = [
                { text: 'Age', type: 'number', required: true, options: [] },
                { text: 'Gender', type: 'radio', required: true, options: ['Male', 'Female', 'Other'] },
                { text: 'Location', type: 'text', required: true, options: [] }
            ];

            let demographicsSectionIndex = sections.findIndex(section => section.title.toLowerCase() === 'demographics');

            if (demographicsSectionIndex < 0) {
                sections.push({
                    title: 'Demographics',
                    description: 'Default demographic information for respondents.',
                    questions: []
                });
                demographicsSectionIndex = sections.length - 1;
            }

            demographicQuestions.forEach(question => {
                sections[demographicsSectionIndex].questions.push(question);
            });

            renderSections();
        }

        function saveQuestion() {
            const text = document.getElementById('modalQuestionText').value.trim();
            const type = document.getElementById('modalQuestionType').value;
            const required = document.getElementById('modalRequired').checked;
            const optionsRaw = document.getElementById('modalOptions').value.trim();

            if (!text) {
                alert('Please enter a question.');
                return;
            }

            if (typesWithOptions.includes(type) && !optionsRaw) {
                alert('Please enter at least one option.');
                return;
            }

            const options = typesWithOptions.includes(type)
                ? optionsRaw.split('\n').map(o => o.trim()).filter(o => o.length > 0)
                : [];

            if (editingQuestionIndex >= 0) {
                sections[currentQuestionSectionIndex].questions[editingQuestionIndex] = { text, type, required, options };
            } else {
                sections[currentQuestionSectionIndex].questions.push({ text, type, required, options });
            }

            renderSections();
            closeQuestionModal();
        }

        function removeQuestion(sectionIndex, questionIndex) {
            sections[sectionIndex].questions.splice(questionIndex, 1);
            renderSections();
        }

        function renderSections() {
            const list = document.getElementById('sectionsList');
            const noSections = document.getElementById('noSections');

            if (sections.length === 0) {
                list.innerHTML = '';
                noSections.classList.remove('hidden');
                updateSerializedSections();
                return;
            }

            noSections.classList.add('hidden');
            list.innerHTML = sections.map((section, sectionIndex) => `
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 space-y-2">
                            <input type="text" value="${escapeHtml(section.title)}"
                                oninput="updateSectionField(${sectionIndex}, 'title', this.value)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 focus:outline-none focus:ring-2 focus:border-transparent"
                                placeholder="Section title">
                            <textarea rows="2"
                                oninput="updateSectionField(${sectionIndex}, 'description', this.value)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 focus:outline-none focus:ring-2 focus:border-transparent"
                                placeholder="Section description (optional)">${escapeHtml(section.description || '')}</textarea>
                        </div>
                        <button type="button" onclick="removeSection(${sectionIndex})"
                            class="text-red-400 hover:text-red-600 text-xl leading-none cursor-pointer" title="Remove Section">&times;</button>
                    </div>

                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs text-gray-400">${section.questions.length} question(s)</p>
                        <button type="button" onclick="openQuestionModal(${sectionIndex})"
                            class="px-3 py-1.5 rounded-md text-xs font-semibold transition hover:opacity-90 cursor-pointer"
                            style="background-color: #f3c404; color: #004179;">
                            Add Question
                        </button>
                    </div>

                    <div class="space-y-2">
                        ${section.questions.length === 0 ? '<p class="text-sm text-gray-400 py-2">No questions in this section yet.</p>' : section.questions.map((question, questionIndex) => `
                            <div class="flex items-start gap-3 border border-gray-100 rounded-lg p-3 bg-gray-50">
                                <span class="text-sm font-semibold text-gray-400 mt-0.5">${questionIndex + 1}.</span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">${escapeHtml(question.text)} ${question.required ? '<span class="text-red-500">*</span>' : ''}</p>
                                    <p class="text-xs text-gray-400 mt-1">${typeLabels[question.type] || question.type}${question.options.length ? ' &middot; ' + question.options.length + ' options' : ''}</p>
                                </div>
                                <button type="button" onclick="openQuestionModal(${sectionIndex}, ${questionIndex})"
                                    class="text-blue-400 hover:text-blue-600 text-sm cursor-pointer" title="Edit">&#9998;</button>
                                <button type="button" onclick="removeQuestion(${sectionIndex}, ${questionIndex})"
                                    class="text-red-400 hover:text-red-600 text-sm cursor-pointer" title="Remove">&times;</button>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `).join('');

            updateSerializedSections();
        }

        function updateSerializedSections() {
            document.getElementById('sectionsJson').value = JSON.stringify(sections);
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            const questionCount = sections.reduce((total, section) => total + section.questions.length, 0);

            if (sections.length === 0 || questionCount === 0) {
                e.preventDefault();
                alert('Please add at least one section and one question.');
                return;
            }

            updateSerializedSections();
        });
    </script>
</x-layout>
