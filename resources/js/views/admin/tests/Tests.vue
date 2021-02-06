<template>
    <div>
        <CModal
            title="Удалить тест?"
            color="danger"
            :show.sync="isDeleteModalShown"
        >
            Вы уверены, что хотите удалить тест?
            <template slot="footer">
                <CButton
                    color="secondary"
                    @click="isDeleteModalShown = false"
                >
                    Отмена
                </CButton>
                <CButton
                    color="danger"
                    @click="deleteTest()"
                >
                    Удалить
                </CButton>
            </template>
        </CModal>
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
                            <CButton
                                color="primary"
                                class="float-right"
                                @click="createTest()"
                            >
                                Создать
                            </CButton>
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
                                <template #actions="{item}">
                                    <td>
                                        <CButtonGroup>
                                            <CButton color="secondary" @click="showTest( item.id )">Показать</CButton>
                                            <CButton color="secondary" @click="editTest( item.id )">Редактировать
                                            </CButton>
                                            <CButton color="danger" @click="showDeleteModal(item.id)">Удалить</CButton>
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
            isDeleteModalShown: false,
            deleteItem: null,
            items: [],
            fields: [
                {key: 'id', label: '#'},
                {key: 'title', label: 'Название'},
                {key: 'description', label: 'Описание'},
                {key: 'questions_count', label: 'Кол-во вопросов'},
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
        createTest() {
            this.$router.push({path: 'tests/create'})
        },
        testLink(id) {
            return `tests/${id.toString()}`
        },
        editLink(id) {
            return `tests/${id.toString()}/edit`
        },
        showTest(id) {
            const testLink = this.testLink(id);
            this.$router.push({path: testLink});
        },
        editTest(id) {
            const editLink = this.editLink(id);
            this.$router.push({path: editLink});
        },
        showDeleteModal(id) {
            this.deleteItem = id;
            this.isDeleteModalShown = true;
        },
        deleteTest() {
            api.admin.tests.delete(this.deleteItem)
                .then(() => {
                    this.isDeleteModalShown = false;
                    this.message = 'Тест удален.';
                    this.showAlert();
                    this.getTests();
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.addErrors(error)))
                    } else {
                        this.addErrors('Что-то пошло не так')
                    }
                })
        },
        countDownChanged(dismissCountDown) {
            this.dismissCountDown = dismissCountDown
        },
        showAlert() {
            this.dismissCountDown = this.dismissSecs
        },
        getTests(page = 1) {
            let self = this;
            api.admin.tests.load({page})
                .then(function (response) {
                    self.items = response.data.body;
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
