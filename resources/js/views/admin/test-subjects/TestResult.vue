<template>
    <CRow>
        <CToaster :autohide="3000">
            <template v-for="(error, k) in errors">
                <CToast
                    :key="'error' + k"
                    :show="true"
                    class="bg-danger text-white"
                >
                    {{ error }}.
                </CToast>
            </template>
        </CToaster>
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
                    <TestResultTable
                        v-if="test"
                        :questions="test.questions"
                    />
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
import TestResultTable from "../../../components/TestResultTable";

export default {
    name: 'TestResult',
    components: {TestResultTable},
    data: () => {
        return {
            message: '',
            dismissSecs: 3,
            dismissCountDown: 0,
            passed_at: null,
            test: null,
            testSubjectId: 0,
            userTestId: 0,
            errors: [],
        }
    },
    methods: {
        showAlert(message) {
            this.message = message
            this.dismissCountDown = this.dismissSecs
        },
        load() {
            api.admin.testSubjects.showResult(this.testSubjectId, this.userTestId)
                .then((response) => {
                    let userTest = response.data.data
                    this.test = userTest.test
                    this.passed_at = userTest.passed_at
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                    } else {
                        this.addErrors('Что-то пошло не так')
                    }
                })
        },
        goBack() {
            this.$router.go(-1)
        },
        addErrors(message) {
            this.errors.push(message)
        }
    },
    computed: {},
    mounted() {
        this.testSubjectId = this.$route.params.testSubjectId
        this.userTestId = this.$route.params.userTestId
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
