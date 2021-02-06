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
                            <p class="text-muted">Введите логин и пароль</p>
                            <CInput
                                v-model="email"
                                prependHtml="<i class='cui-user'></i>"
                                placeholder="Email"
                                autocomplete="username email"
                            >
                                <template #prepend-content>
                                    <CIcon name="cil-user"/>
                                </template>
                            </CInput>
                            <CInput
                                v-model="password"
                                prependHtml="<i class='cui-lock-locked'></i>"
                                placeholder="Пароль"
                                type="password"
                                autocomplete="curent-password"
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
            email: '',
            password: '',
            message: '',
            dismissSecs: 3,
            dismissCountDown: 0,
        }
    },
    methods: {
        goRegister() {
            this.$router.push({path: 'register'});
        },
        login() {
            api.auth.adminLogin({
                email: this.email,
                password: this.password,
            })
                .then(response => {
                    //TODO поправить. как-то не круто
                    Vue.http.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;
                    localStorage.setItem("api_token", response.data.access_token);
                    localStorage.setItem('roles', response.data.roles);
                    this.$router.push({path: '/admin/'});
                })
                .catch(error => {
                    this.message = 'Неверный email или пароль';
                    this.showAlert()
                    console.log(error);
                });

        },
        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
    }
}

</script>
