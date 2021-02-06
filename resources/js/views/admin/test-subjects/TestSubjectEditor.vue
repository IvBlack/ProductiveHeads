<template>
    <CCol col="12" xl="8">
        <CCard>
            <CCardHeader>
                Информация
            </CCardHeader>
            <CCardBody>
                <CInput
                    label="Фамилия"
                    horizontal
                    placeholder="Фамилия..."
                    v-model="test_subject.last_name"
                    :isValid="last_name => validateString(test_subject.last_name_used, last_name)"
                    invalid-feedback="Фамилия обязательна"
                    @input="test_subject.last_name_used = true"
                />
                <CInput
                    label="Имя"
                    horizontal
                    placeholder="Имя..."
                    v-model="test_subject.first_name"
                    :isValid="first_name => validateString(test_subject.first_name_used, first_name)"
                    invalid-feedback="Имя обязательно"
                    @input="test_subject.first_name_used = true"
                />
                <CInput
                    label="Отчество"
                    horizontal
                    placeholder="Отчество..."
                    v-model="test_subject.middle_name"
                />
                <CInput
                    label="Телефон"
                    horizontal
                    placeholder="Телефон..."
                    v-model="test_subject.phone"
                    :isValid="validatePhone"
                    invalid-feedback="Не правильный телефон"
                />
                <CInput
                    label="Email"
                    horizontal
                    placeholder="Email..."
                    v-model="test_subject.email"
                    :isValid="validateEmail"
                    invalid-feedback="Не правильный email"
                    type="email"
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
    </CCol>
</template>

<script>

export default {
    name: 'TestSubjectEditor',
    props: {
        test_subject: {
            Object,
            default() {
                return {
                    first_name: '',
                    first_name_used: false,
                    last_name: '',
                    last_name_used: false,
                    middle_name: '',
                    email: '',
                    phone: ''
                }
            }
        },
    },
    methods: {
        validateString(used, string) {
            if (!used) {
                return null
            }

            if (string) {
                return null
            }

            return false;
        },
        validateEmail(email) {
            if (email === 0) {
                return null
            }
            const regex = /^[\w-.]{2,}@[\w-]{2,}.[\w-]{2,}$/gm;
            return regex.test(email) ? null : false
        },
        validatePhone(phone) {
            if (phone === 0) {
                return null
            }
            const regex = /^(\+7)\d{10}$/gm;
            return regex.test(phone) ? null : false
        },
        save(e) {
            let valid = true
            this.test_subject.first_name_used = true
            this.test_subject.last_name_used = true

            if (!this.test_subject.first_name) {
                this.addErrors('Имя обязательно')
                valid = false
            }
            if (!this.test_subject.last_name) {
                this.addErrors('Фамилия обязательна')
                valid = false
            }

            if (!valid) {
                return
            }


            this.$emit('save', this.test_subject, e)
        },
        addErrors(message) {
            this.$emit('error', message)
        },
    },
}
</script>
