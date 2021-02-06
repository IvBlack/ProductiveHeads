<template>
    <CRow>
        <CCol col="12" lg="6">
            <CCard no-header>
                <CCardBody>
                    <CForm>
                        <template slot="header">
                            Edit User id: {{ $route.params.id }}
                        </template>
                        <CAlert
                            :show.sync="dismissCountDown"
                            color="primary"
                            fade
                        >
                            ({{ dismissCountDown }}) {{ message }}
                        </CAlert>
                        <CInput type="text" label="Name" placeholder="Name" v-model="name"></CInput>
                        <CInput type="text" label="Email" placeholder="Email" v-model="email"></CInput>
                        <CButton color="primary" @click="update()">Сохранить</CButton>
                        <CButton color="primary" @click="goBack">Назад</CButton>
                    </CForm>
                </CCardBody>
            </CCard>
        </CCol>
    </CRow>
</template>

<script>
import api from '@/api'

export default {
    name: 'EditUser',
    props: {
        caption: {
            type: String,
            default: 'User id'
        },
    },
    data: () => {
        return {
            name: '',
            email: '',
            showMessage: false,
            message: '',
            dismissSecs: 7,
            dismissCountDown: 0,
            showDismissibleAlert: false
        }
    },
    methods: {
        goBack() {
            this.$router.go(-1)
            // this.$router.replace({path: '/users'})
        },
        update() {
            let self = this;
            self.message = 'Successfully updated user.';
            self.showAlert();
            return
            api.admin.users.update(self.$route.params.id,
                {
                    name: self.name,
                    email: self.email,
                })
                .then(function (response) {
                    self.message = 'Successfully updated user.';
                    self.showAlert();
                }).catch(function (error) {
                console.log(error);
                self.$router.push({path: '/login'});
            });
        },
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
    },
    mounted: function () {
        let self = this;
        api.admin.users.show(self.$route.params.id)
            .then(function (response) {
                self.name = response.data.name;
                self.email = response.data.email;
            }).catch(function (error) {
            console.log(error);
            self.$router.push({path: '/login'});
        });
    }
}


</script>
