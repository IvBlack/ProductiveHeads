<template>
    <CContainer class="d-flex content-center min-vh-100">
        <CRow>
            <CCol>
                <CCard class="p-4">
                    <CCardBody>
                        <CForm @submit.prevent="login" method="POST">
                            <CAlert
                                :show.sync="dismissCountDown"
                                color="danger"
                                fade
                            >
                                {{ message }}
                            </CAlert>
                            <h1>Вход</h1>
                            <p class="text-muted">Введите телефон и код</p>
                            <CInput
                                v-model="phone"
                                prependHtml="<i class='cui-user'></i>"
                                placeholder="Телефон"
                                autocomplete="phone"
                            >
                                <template #prepend-content>
                                    <CIcon name="cil-user"/>
                                </template>
                            </CInput>
                            <CInput
                                v-model="password"
                                prependHtml="<i class='cui-lock-locked'></i>"
                                placeholder="Пароль"
                                type="text"
                                autocomplete="current-password"
                            >
                                <template #prepend-content>
                                    <CIcon name="cil-lock-locked"/>
                                </template>
                            </CInput>
                            <CRow>
                                <CCol col="12">
                                    <CButton type="submit" color="primary" class="px-4">Войти</CButton>
                                </CCol>
                            </CRow>
                        </CForm>
                    </CCardBody>
                </CCard>
            </CCol>
        </CRow>
    </CContainer>
</template>

<script>
import api from '@/api'
import Vue from 'vue'

export default {
    name: 'Login',
    data() {
        return {
            phone: '',
            password: '',
            message: '',
            dismissSecs: 3,
            dismissCountDown: 0,
        }
    },
    methods: {
        login() {
            api.auth.login({
                phone: this.phone,
                auth_code: this.password,
            })
                .then(response => {
                    //TODO поправить. как-то не круто
                    Vue.http.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
                    localStorage.setItem("api_token", response.data.access_token);
                    localStorage.setItem('roles', response.data.roles);
                    this.$router.push({path: '/'});
                })
                .catch(error => {
                    this.message = 'Неверный телефон или код';
                    this.showAlert()
                    console.log(error);
                });

        },
        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
    },
    mounted() {
        this.phone = this.$route.query.phone ? '+' + this.$route.query.phone.trim() : ''
        this.password = this.$route.query.code || ''
    }
}

</script>
