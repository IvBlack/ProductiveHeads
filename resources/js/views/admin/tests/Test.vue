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
                <CCardHeader>
                    Test #{{ $route.params.id }}
                    <CButton
                        color="primary"
                        class="float-right"
                        @click="editTest()"
                    >
                        Редактировать
                    </CButton>
                </CCardHeader>
                <CCardBody>
                    <CRow class="mb-4">
                        <CCol>
                            <h3 v-html="test.title"/>
                            <h6 v-html="test.description"/>
                        </CCol>
                    </CRow>
                    <CDataTable
                        v-if="test"
                        striped
                        small
                        fixed
                        :items="test.questions"
                        :fields="questionFields"
                    >
                        <template #key="{index}">
                            <td>{{ index + 1 }}</td>
                        </template>
                        <template #question="{item}">
                            <td v-html="item.question"/>
                        </template>
                        <template #description="{item}">
                            <td v-html="item.description"/>
                        </template>
                        <template #possible_answers="{item}">
                            <td v-html="splitAnswers(item.possible_answers)"/>
                        </template>
                    </CDataTable>
                </CCardBody>
                <CCardFooter>
                    <CButton color="primary" @click="goBack">Назад</CButton>
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
            test: {},
            questionFields: [
                {
                    key: 'key',
                    label: '#'
                },
                {
                    key: 'question',
                    label: 'Вопрос'
                },
                {
                    key: 'description',
                    label: 'Описание'
                },
                {
                    key: 'possible_answers',
                    label: 'Ответы'
                },
            ]
        }
    },
    methods: {
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert(message) {
            this.message = message
            this.dismissCountDown = this.dismissSecs
        },
        goBack() {
            this.$router.go(-1)
        },
        splitAnswers(answers) {
            return answers.map(answer => answer.answer).join(', ')
        },
        editLink() {
            return `/admin/tests/${this.$route.params.id.toString()}/edit`
        },
        editTest() {
            const editLink = this.editLink();
            this.$router.push({path: editLink});
        },
    },
    computed: {},
    mounted() {
        api.admin.tests.show(this.$route.params.id)
            .then((response) => {
                this.test = response.data.body
            })
            .catch(({status, data}) => {
                if (status === 422) {
                    Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                } else {
                    this.addErrors('Что-то пошло не так')
                }
            })

        if (this.$route.query.message) {
            this.showAlert(this.$route.query.message)
        }
    }
}
</script>
