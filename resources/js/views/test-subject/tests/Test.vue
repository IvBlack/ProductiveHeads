<template>
    <CRow>
        <CCol>
            <CCard>
                <CAlert
                    :show.sync="dismissCountDown"
                    color="primary"
                    fade
                >
                    ({{ dismissCountDown }}) {{ message }}
                </CAlert>
                <CCardHeader v-if="test">
                    {{ test.title }} ({{ test.description }})
                </CCardHeader>
                <CCardBody>
                    <template v-if="currentQuestion">
                        <div class="lead" v-html="currentQuestion.question"></div>
                        <p v-html="currentQuestion.description"></p>
                        <CListGroup class="col-4 col-md-6 col-sm-12">
                            <CListGroupItem
                                class="possible-answer"
                                v-for="answer in currentQuestion.possible_answers"
                                v-bind:key="answer.id"
                                :color="selectedAnswerIds.includes(answer.id) ? 'dark' : 'light'"
                                @click="selectAnswer(answer.id)"
                                action
                                v-html="answer.answer"
                            />
                        </CListGroup>
                    </template>
                    <template v-else>
                        <div class="lead">Тест пройден</div>
                    </template>
                </CCardBody>
                <CCardFooter>
                    <CButton
                        v-if="currentQuestion"
                        color="primary"
                        @click="answer"
                        :disabled="!selectedAnswerIds.length"
                    >
                        Ответить
                    </CButton>
                    <CButton
                        color="primary"
                        @click="showTest"
                        v-else
                    >
                        Посмотреть результаты
                    </CButton>
                </CCardFooter>
            </CCard>
        </CCol>
    </CRow>
</template>

<script>
import api from '@/api'


export default {
    name: 'Test',
    data: () => {
        return {
            message: '',
            dismissSecs: 3,
            dismissCountDown: 0,
            passed_at: null,
            test: null,
            userTestId: 0,
            selectedAnswerIds: [],
            currentQuestion: {}
        }
    },
    methods: {
        showAlert(message) {
            this.message = message
            this.dismissCountDown = this.dismissSecs
        },
        selectAnswer(answerId) {
            if (this.currentQuestion.multiple_answers) {
                let key = this.selectedAnswerIds.indexOf(answerId)
                if (key === -1) {
                    this.selectedAnswerIds.push(answerId)
                } else {
                    this.selectedAnswerIds.splice(key, 1)
                }
            } else {
                this.selectedAnswerIds = [answerId]
            }
        },
        load() {
            api.testSubject.tests.show(this.userTestId)
                .then((response) => {
                    let userTest = response.data.data
                    this.test = userTest.test
                    this.passed_at = userTest.passed_at
                    this.computeCurrentQuestion()
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                    } else {
                        this.addErrors('Что-то пошло не так')
                    }
                })
        },
        answer() {
            api.testSubject.tests.answer(this.userTestId, this.currentQuestion.id, this.selectedAnswerIds)
                .then(() => {
                    this.test.questions.find(question => question === this.currentQuestion).answer_ids = this.selectedAnswerIds
                    this.selectedAnswerIds = []
                    this.computeCurrentQuestion()
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                    } else {
                        this.addErrors('Что-то пошло не так')
                    }
                })
        },
        computeCurrentQuestion() {
            this.currentQuestion = this.test.questions ? this.test.questions.find(question => !question.answer_ids.length) : null
        },
        showTest() {
            this.$router.push({path: `/tests/${this.userTestId.toString()}/result`});
        },
    },
    computed: {},
    mounted() {
        this.userTestId = this.$route.params.id
        this.load()
        if (this.$route.query.message) {
            this.showAlert(this.$route.query.message)
        }
    }
}
</script>
<style scoped>
.possible-answer {
    cursor: pointer;
}
</style>
