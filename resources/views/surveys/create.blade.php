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
                    <h2 class="text-lg font-semibold" style="color: #004179;">Questions</h2>
                    <div class="flex gap-3">
                        <button type="button" onclick="addDemographicInfo()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition hover:opacity-90 cursor-pointer bg-gray-300 hover:bg-gray-400">
                            Require Demographic
                        </button>
                        <button type="button" onclick="openModal()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition hover:opacity-90 cursor-pointer"
                            style="background-color: #f3c404; color: #004179;">
                            Add Question
                        </button>
                    </div>
                </div>
                <div id="questionsList" class="space-y-3">
                </div>
                <p id="noQuestions" class="text-gray-400 text-sm py-8 text-center border border-dashed border-gray-300 rounded-lg">
                    No questions added yet. Click "Add Question" to get started.
                </p>
            </div>

            <input type="hidden" name="questions_json" id="questionsJson">

            <button type="submit"
                class="px-8 py-2.5 rounded-lg text-white font-semibold transition hover:opacity-90 cursor-pointer"
                style="background-color: #004179;">
                Create Survey
            </button>
            <a href="{{ route('surveys.my') }}" class="ml-4 text-sm text-gray-500 hover:text-gray-700">&larr; Back to My Surveys</a>
        </form>
    </div>

    <div id="questionModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-lg mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 id="modalTitle" class="text-lg font-semibold" style="color: #004179;">Add Question</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer text-xl">&times;</button>
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
                <button type="button" onclick="closeModal()"
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
        let questions = [];
        let editingIndex = -1;
        const typesWithOptions = ['radio', 'checkbox', 'dropdown'];
        const typeLabels = {
            text: 'Text', textarea: 'Textarea', radio: 'Radio', checkbox: 'Checkbox',
            dropdown: 'Dropdown', date: 'Date', time: 'Time', number: 'Number', email: 'Email', scale: 'Scale'
        };

        function openModal() {
            editingIndex = -1;
            document.getElementById('modalTitle').textContent = 'Add Question';
            document.getElementById('modalSaveLabel').textContent = 'Add Question';
            document.getElementById('modalQuestionText').value = '';
            document.getElementById('modalQuestionType').value = 'text';
            document.getElementById('modalOptions').value = '';
            document.getElementById('modalRequired').checked = false;
            toggleOptionsField();
            const modal = document.getElementById('questionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function editQuestion(index) {
            editingIndex = index;
            const q = questions[index];
            document.getElementById('modalTitle').textContent = 'Edit Question';
            document.getElementById('modalSaveLabel').textContent = 'Save Changes';
            document.getElementById('modalQuestionText').value = q.text;
            document.getElementById('modalQuestionType').value = q.type;
            document.getElementById('modalOptions').value = q.options.join('\n');
            document.getElementById('modalRequired').checked = q.required;
            toggleOptionsField();
            const modal = document.getElementById('questionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('questionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            editingIndex = -1;
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

            demographicQuestions.forEach(q => questions.push(q));
            renderQuestions();
        }

        function saveQuestion() {
            const text = document.getElementById('modalQuestionText').value.trim();
            const type = document.getElementById('modalQuestionType').value;
            const required = document.getElementById('modalRequired').checked;
            const optionsRaw = document.getElementById('modalOptions').value.trim();

            console.log(text, type, required, optionsRaw);

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

            if (editingIndex >= 0) {
                questions[editingIndex] = { text, type, required, options };
            } else {
                questions.push({ text, type, required, options });
            }
            renderQuestions();
            closeModal();
        }

        function removeQuestion(index) {
            questions.splice(index, 1);
            renderQuestions();
        }

        function renderQuestions() {
            const list = document.getElementById('questionsList');
            const noQ = document.getElementById('noQuestions');

            if (questions.length === 0) {
                list.innerHTML = '';
                noQ.classList.remove('hidden');
                return;
            }

            noQ.classList.add('hidden');
            list.innerHTML = questions.map((q, i) => `
                <div class="flex items-start gap-3 bg-white border border-gray-200 rounded-lg p-4">
                    <span class="text-sm font-semibold text-gray-400 mt-0.5">${i + 1}.</span>
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">${escapeHtml(q.text)} ${q.required ? '<span class="text-red-500">*</span>' : ''}</p>
                        <p class="text-xs text-gray-400 mt-1">${typeLabels[q.type] || q.type}${q.options.length ? ' &middot; ' + q.options.length + ' options' : ''}</p>
                    </div>
                    <button type="button" onclick="editQuestion(${i})"
                        class="text-blue-400 hover:text-blue-600 text-sm cursor-pointer" title="Edit">&#9998;</button>
                    <button type="button" onclick="removeQuestion(${i})"
                        class="text-red-400 hover:text-red-600 text-sm cursor-pointer">&times;</button>
                </div>
            `).join('');

            document.getElementById('questionsJson').value = JSON.stringify(questions);
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            if (questions.length === 0) {
                e.preventDefault();
                alert('Please add at least one question.');
                return;
            }
            document.getElementById('questionsJson').value = JSON.stringify(questions);
        });
    </script>
</x-layout>
