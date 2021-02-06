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
                :test_subject="test_subject"
            />
        </CRow>
    </div>
</template>

<script>

import api from '@/api'
import TestSubjectEditor from "./TestSubjectEditor";

export default {
    name: 'Update',
    components: {
        TestSubjectEditor
    },
    data: () => {
        return {
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            test_subject: {},
            errors: []
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
            api.admin.testSubjects.update(this.$route.params.id, test_subject)
                .then(({data}) => {
                    this.$router.push({path: `/admin/test-subjects/${data.body.id}?message=Тестируемый отредактирован`});
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
    mounted() {
        api.admin.testSubjects.show(this.$route.params.id)
            .then((response) => {
                this.test_subject = response.data.test_subject
            }).catch((error) => {
            console.log(error);
            this.$router.push({path: '/login'});
        });
    }
}
</script>
