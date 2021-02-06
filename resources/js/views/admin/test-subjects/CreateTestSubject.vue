<template>
    <div>
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
        <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
        >
            ({{ dismissCountDown }}) {{ message }}
        </CAlert>
        <CRow>
            <TestSubjectEditor
                @save="save"
                @error="addErrors"
                import="true"
            />
        </CRow>
    </div>
</template>

<script>

import api from '@/api'
import TestSubjectEditor from "./TestSubjectEditor";

export default {
    name: 'CreateTestSubject',
    components: {
        TestSubjectEditor
    },
    data: () => {
        return {
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            errors: [],
        }
    },
    methods: {
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
        save(test_subject) {
            api.admin.testSubjects.create(test_subject)
                .then(({ data }) => {
                    this.$router.push({path: `/admin/test-subjects/${data.body.id}?message=Тестируемый создан`});
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                    } else {
                        this.addErrors('Что-то пошло не так')
                    }
                })
        },
        addErrors(message) {
            this.errors.push(message)
        }
    },
}
</script>
