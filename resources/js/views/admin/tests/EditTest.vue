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
            <TestEditor
                @save="save"
                @error="addErrors"
                :test="test"
            />
        </CRow>
    </div>
</template>

<script>

import api from '@/api'
import TestEditor from "./TestEditor";

export default {
    name: 'Update',
    components: {
        TestEditor
    },
    data: () => {
        return {
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            test: {},
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
        save(test) {
            api.admin.tests.update(this.$route.params.id, {
                id: test.id,
                title: test.title,
                description: test.description,
                questions: test.questions.map(({id, question, description, possible_answers, multiple_answers}, sequence) => {
                    return {
                        id,
                        question,
                        description,
                        sequence,
                        multiple_answers,
                        possible_answers: possible_answers.map(({id, answer}, sequence) => {
                            return {
                                id,
                                answer,
                                sequence
                            }
                        })
                    }
                })
            })
                .then(({data}) => {
                    this.$router.push({path: `/admin/tests/${data.body.id}?message=Тест отредактирован`});
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
    }
}
</script>
