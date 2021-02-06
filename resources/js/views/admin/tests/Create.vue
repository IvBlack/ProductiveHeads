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
                import="true"
            />
        </CRow>
    </div>
</template>

<script>

import api from '@/api'
import TestEditor from "./TestEditor";

export default {
    name: 'Create',
    components: {
        TestEditor
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
        save(test) {
            api.admin.tests.create({
                title: test.title,
                description: test.description,
                questions: test.questions.map(({question, description, possible_answers, multiple_answers}, sequence) => {
                    return {
                        question,
                        description,
                        sequence,
                        multiple_answers,
                        possible_answers: possible_answers.map(({answer}, sequence) => {
                            return {
                                answer,
                                sequence
                            }
                        })
                    }
                })
            })
                .then(({ data }) => {
                    this.$router.push({path: `/admin/tests/${data.body.id}?message=Тест создан`});
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
