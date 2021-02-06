<template>
    <CCol col="12" xl="8">
        <CModal
            title="Импортировать вопросы"
            :show.sync="isImportModalShown"
        >
            Выберите файл CSV, с разделителем точка с запятой
            <CInputFile
                id="test"
                @change="fileSelected"
                placeholder="Выберите файл"
            ></CInputFile>
            <template slot="footer">
                <CButton
                    color="secondary"
                    @click="isImportModalShown = false"
                >
                    Отмена
                </CButton>
                <CButton
                    color="primary"
                    @click="importQuestions()"
                    :disabled="!importFile || importLoading"
                >
                    Импортировать
                </CButton>
            </template>
            <CElementCover
                v-show="importLoading"
                style="top: -61px; bottom: -70px; z-index: 1"
                :boundaries="[{query: 'div.modal-content' }]"
                :opacity="0.8"
            >
                <h1 class="d-inline">Импорт... </h1>
                <CSpinner size="5xl" color="success"/>
            </CElementCover>
        </CModal>
        <CCard>
            <CCardHeader>
                Информация
            </CCardHeader>
            <CCardBody>
                <CInput
                    label="Название"
                    horizontal
                    placeholder="Название..."
                    v-model="test.title"
                    :isValid="titleString => validateString(test.titleUsed, titleString)"
                    invalid-feedback="Заголовок обязательный"
                    @input="test.titleUsed = true"
                />
                <CTextarea
                    label="Описание"
                    horizontal
                    placeholder="Описание..."
                    v-model="test.description"
                />
            </CCardBody>
            <CCardFooter>
                <CButton
                    color="primary"
                    @click="save"
                >
                    <CIcon name="cil-save"/>
                    Сохранить
                </CButton>
            </CCardFooter>
        </CCard>
        <CCard>
            <CCardHeader>
                Вопросы
                <CButtonGroup
                    class="float-right"
                >
                    <CButton
                        color="info"
                        @click="isImportModalShown = true"
                    >
                        Импорт
                    </CButton>
                    <CButton
                        color="primary"
                        @click="addQuestion"
                    >
                        Добавить вопрос
                    </CButton>
                </CButtonGroup>
            </CCardHeader>
            <CCardBody>
                <CListGroup>
                    <CListGroupItem v-for="(question, questionKey) in test.questions" :key="questionKey">
                        <div class="form-row form-group"># {{ questionKey + 1 }}</div>
                        <CInput
                            label="Вопрос"
                            horizontal
                            placeholder="Вопрос..."
                            v-model="question.question"
                            :isValid="questionString => validateString(question.used, questionString)"
                            invalid-feedback="Вопрос обязательный"
                            @input="question.used = true"
                        />
                        <CTextarea
                            label="Описание"
                            horizontal
                            placeholder="Описание..."
                            v-model="question.description"
                        />
                        <div role="group" class="form-group form-row">
                            <CCol sm="3" class="col-form-label">
                                Ответы
                                <CButton
                                    color="primary"
                                    class="float-right"
                                    size="sm"
                                    @click="addAnswer(questionKey)"
                                >
                                    <CIcon name="cil-plus"/>
                                </CButton>
                            </CCol>
                            <CCol sm="9">
                                <CInput
                                    v-for="(answer, answerKey) in question.possible_answers"
                                    :key="answerKey"
                                    placeholder="Ответ..."
                                    v-model="answer.answer"
                                    :isValid="answerString => validateString(answer.used, answerString)"
                                    invalid-feedback="Ответ обязательный"
                                    @input="answer.used = true"
                                >
                                    <template
                                        #append
                                        v-if="question.possible_answers.length > 1"
                                    >
                                        <CButton
                                            color="primary"
                                            @click="answerDown(questionKey, answerKey)"
                                            :disabled="answerKey === question.possible_answers.length - 1"
                                        >
                                            <CIcon name="cil-level-down"/>
                                        </CButton>
                                        <CButton
                                            color="primary"
                                            @click="answerUp(questionKey, answerKey)"
                                            :disabled="answerKey === 0"
                                        >
                                            <CIcon name="cil-level-up"/>
                                        </CButton>
                                        <CButton
                                            color="danger"
                                            @click="deleteAnswer(questionKey, answerKey)"
                                        >
                                            <CIcon name="cil-x"/>
                                        </CButton>
                                    </template>
                                </CInput>
                            </CCol>
                        </div>
                        <CInputCheckbox
                            label="Возможно несколько ответов"
                            horizontal
                            :value="question.multiple_answers"
                            :checked.sync="question.multiple_answers"
                        />
                        <template
                            v-if="test.questions.length > 1"
                        >
                            <CButton
                                color="primary"
                                @click="questionDown(questionKey)"
                                :disabled="questionKey === test.questions.length - 1"
                            >
                                <CIcon name="cil-level-down"/>
                                Ниже
                            </CButton>
                            <CButton
                                color="primary"
                                @click="questionUp(questionKey)"
                                :disabled="questionKey === 0"
                            >
                                <CIcon name="cil-level-up"/>
                                Выше
                            </CButton>
                            <CButton
                                color="danger"
                                @click="deleteQuestion(questionKey)"
                            >
                                <CIcon name="cil-x"/>
                                Удалить
                            </CButton>
                        </template>
                    </CListGroupItem>
                </CListGroup>
            </CCardBody>
            <CCardFooter>
                <CButton
                    color="primary"
                    @click="save"
                >
                    <CIcon name="cil-save"/>
                    Сохранить
                </CButton>
            </CCardFooter>
        </CCard>
    </CCol>
</template>

<script>

import api from '@/api';

export default {
    name: 'TestEditor',
    props: {
        test: {
            Object,
            default() {
                return {
                    titleUsed: false,
                    title: '',
                    description: '',
                    questions: [
                        {
                            used: false,
                            question: '',
                            description: '',
                            sequence: 0,
                            multiple_answers: false,
                            possible_answers: [
                                {
                                    used: false,
                                    answer: 'Да',
                                    description: '',
                                    sequence: 0
                                },
                                {
                                    answer: '-',
                                    description: '',
                                    sequence: 1
                                },
                                {
                                    answer: 'Нет',
                                    description: '',
                                    sequence: 2
                                },
                            ]
                        }
                    ],
                }

            }
        },
        import: {
            Boolean,
            default: false,
        }
    },
    data: () => {
        return {
            isImportModalShown: false,
            importFile: null,
            importLoading: false,
        }
    },
    methods: {
        addQuestion() {
            this.test.questions.push({
                    used: false,
                    question: '',
                    description: '',
                    sequence: 0,
                    possible_answers: [
                        {
                            used: false,
                            answer: 'Да',
                            description: '',
                            sequence: 0
                        },
                        {
                            used: false,
                            answer: '-',
                            description: '',
                            sequence: 1
                        },
                        {
                            used: false,
                            answer: 'Нет',
                            description: '',
                            sequence: 2
                        },
                    ]
                }
            )
        },
        deleteQuestion(questionKey) {
            this.test.questions.splice(questionKey, 1)
        },
        questionUp(questionKey) {
            this.swap(this.test.questions, questionKey, questionKey - 1)
        },
        questionDown(questionKey) {
            this.swap(this.test.questions, questionKey, questionKey + 1)
        },
        addAnswer(questionKey) {
            this.test.questions[questionKey].possible_answers.push({
                used: false,
                answer: '',
                description: '',
                sequence: 2
            })
        },
        deleteAnswer(questionKey, answerKey) {
            this.test.questions[questionKey].possible_answers.splice(answerKey, 1)
        },
        answerUp(questionKey, answerKey) {
            this.swap(this.test.questions[questionKey].possible_answers, answerKey, answerKey - 1)
        },
        answerDown(questionKey, answerKey) {
            this.swap(this.test.questions[questionKey].possible_answers, answerKey, answerKey + 1)
        },
        swap(arr, a, b) {
            arr[a] = arr.splice(b, 1, arr[a])[0]
            return arr
        },
        validateString(used, string) {
            if (!used) {
                return null
            }

            if (string) {
                return null
            }

            return false;
        },
        save(e) {
            let valid = true
            this.test.titleUsed = true

            if (!this.test.title) {
                this.addErrors('Заголовок обязательный')
                valid = false
            }
            this.test.questions.forEach(question => {
                question.used = true
                if (!question.question) {
                    this.addErrors('Вопрос обязательный')
                    valid = false
                }
                question.possible_answers.forEach(answer => {
                    answer.used = true
                    if (!answer.answer) {
                        this.addErrors('Ответ обязательный')
                        valid = false
                    }
                })
            })
            if (!valid) {
                return
            }


            this.$emit('save', this.test, e)
        },
        addErrors(message) {
            this.$emit('error', message)
        },
        importQuestions() {
            this.importLoading = true
            api.admin.tests.import(this.importFile)
                .then(({data}) => {
                    this.test.questions = data
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                    } else {
                        this.addErrors('Что-то пошло не так')
                    }
                })
                .finally(() => {
                    this.isImportModalShown = false
                    this.importFile = null
                    this.importLoading = false
                })
        },
        fileSelected(files) {
            this.importFile = files[0]
        }
    },
}
</script>
