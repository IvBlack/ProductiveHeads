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
        <CRow>
            <CCol col="12" xl="8">
                <transition name="slide">
                    <CCard>
                        <CCardHeader>
                            Тесты
                        </CCardHeader>
                        <CCardBody>
                            <CAlert
                                :show.sync="dismissCountDown"
                                color="primary"
                                fade
                            >
                                ({{ dismissCountDown }}) {{ message }}
                            </CAlert>
                            <CDataTable
                                hover
                                striped
                                :items="items"
                                :fields="fields"
                                :items-per-page="5"
                            >
                                <template slot="id" slot-scope="{item}">
                                    <td>
                                        {{ item.test.id }}
                                    </td>
                                </template>
                                <template slot="title" slot-scope="{item}">
                                    <td>
                                        {{ item.test.title }}
                                    </td>
                                </template>
                                <template slot="description" slot-scope="{item}">
                                    <td>
                                        {{ item.test.description }}
                                    </td>
                                </template>
                                <template slot="passed" slot-scope="{item}">
                                    <td>
                                        {{ item.passed_at ? 'Пройден' : 'Не пройден' }}
                                    </td>
                                </template>
                                <template #actions="{item}">
                                    <td>
                                        <CButtonGroup>
                                            <CButton color="primary" @click="showTest( item.id )" v-if="item.passed_at">Посмотреть</CButton>
                                            <CButton color="primary" @click="passTest( item.id )" v-else>Пройти</CButton>
                                        </CButtonGroup>
                                    </td>
                                </template>
                            </CDataTable>
                            <CPagination v-if="pagination"
                                         :activePage.sync="pagination.current_page"
                                         :pages="pagination.last_page"
                                         @update:activePage="getTests"
                            />
                        </CCardBody>
                    </CCard>
                </transition>
            </CCol>
        </CRow>
    </div>
</template>

<script>
import api from '@/api';

export default {
    name: 'Tests',
    data: () => {
        return {
            items: [],
            fields: [
                {key: 'id', label: '#'},
                {key: 'title', label: 'Название'},
                {key: 'description', label: 'Описание'},
                {key: 'passed', label: 'Пройден'},
                {key: 'actions', label: 'Действия'},
            ],
            pagination: {
                current_page: 1,
                last_page: 1
            },
            message: '',
            showMessage: false,
            dismissSecs: 7,
            dismissCountDown: 0,
            showDismissibleAlert: false,
            errors: [],
        }
    },
    methods: {
        showTest(id) {
            this.$router.push({path: `/tests/${id.toString()}/result`});
        },
        passTest(id) {
            this.$router.push({path: `/tests/${id.toString()}`});
        },
        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
        getTests(page = 1) {
            let self = this;
            api.testSubject.tests.load({page})
                .then(function (response) {
                    self.items = response.data.data;
                    self.pagination = response.data.meta
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
    mounted: function () {
        this.getTests();
    }
}
</script>
