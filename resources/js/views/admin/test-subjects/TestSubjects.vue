<template>
    <div>
        <CModal
            title="Удалить тестируемого?"
            color="danger"
            :show.sync="isDeleteModalShown"
        >
            Вы уверены, что хотите удалить тестируемого?
            <template slot="footer">
                <CButton
                    color="secondary"
                    @click="isDeleteModalShown = false"
                >
                    Отмена
                </CButton>
                <CButton
                    color="danger"
                    @click="deleteTestSubject()"
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
            <CCol>
                <transition name="slide">
                    <CCard>
                        <CCardHeader>
                            Тестируемые
                            <CInput
                                placeholder="Поиск..."
                                v-model="searchText"
                                class="d-inline-block mb-0 ml-2"
                                @input="search"
                            />
                            <CButton
                                color="primary"
                                class="float-right"
                                @click="createTestSubject()"
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
                                            <CButton color="secondary" @click="showTestSubject( item.id )">Показать
                                            </CButton>
                                            <CButton color="secondary" @click="editTestSubject( item.id )">Редактировать
                                            </CButton>
                                            <CButton color="danger" @click="showDeleteModal(item.id)">Удалить</CButton>
                                        </CButtonGroup>
                                    </td>
                                </template>
                            </CDataTable>
                            <CPagination v-if="pagination"
                                         :activePage.sync="pagination.current_page"
                                         :pages="pagination.last_page"
                                         @update:activePage="loadTestSubjects"
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
    name: 'TestSubjects',
    data: () => {
        return {
            searchText: '',
            searchDelayTimer: null,
            isDeleteModalShown: false,
            deleteItem: null,
            items: [],
            fields: [
                {key: 'last_name', label: 'Фамилия'},
                {key: 'first_name', label: 'Имя'},
                {key: 'middle_name', label: 'Отчество'},
                {key: 'phone', label: 'Телефон'},
                {key: 'email', label: 'Email'},
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
        createTestSubject() {
            this.$router.push({path: 'test-subjects/create'})
        },
        testSubjectLink(id) {
            return `test-subjects/${id.toString()}`
        },
        editLink(id) {
            return `test-subjects/${id.toString()}/edit`
        },
        showTestSubject(id) {
            const testSubjectLink = this.testSubjectLink(id);
            this.$router.push({path: testSubjectLink});
        },
        editTestSubject(id) {
            const editLink = this.editLink(id);
            this.$router.push({path: editLink});
        },
        showDeleteModal(id) {
            this.deleteItem = id;
            this.isDeleteModalShown = true;
        },
        deleteTestSubject() {
            api.admin.testSubjects.delete(this.deleteItem)
                .then(() => {
                    this.isDeleteModalShown = false;
                    this.message = 'Тестируемый удален.';
                    this.showAlert();
                    this.loadTestSubjects();
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
        loadTestSubjects(page = 1) {
            api.admin.testSubjects.load({
                page,
                search: this.searchText,
            })
                .then((response) => {
                    this.items = response.data.body;
                    this.pagination = response.data.meta
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
        },
        search() {
            clearTimeout(this.searchDelayTimer)
            this.searchDelayTimer = setTimeout(() => {
                this.loadTestSubjects()
            }, 1000);
        }
    },
    mounted: function () {
        this.loadTestSubjects();
    }
}
</script>
