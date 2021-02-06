<template>
    <CRow>
        <CToaster :autohide="3000">
            <template v-for="(message, k) in messages">
                <CToast
                    :key="'message' + k"
                    :show="true"
                    :class="{
                        'bg-danger': message.type === 'error',
                         'text-white': message.type === 'error'
                    }"
                >
                    {{ message.message }}.
                </CToast>
            </template>
        </CToaster>
        <CModal
            title="Добавить тест"
            :show.sync="isAddTestModalShown"
        >
            <div class="authors-base">
                <TestSearch
                    v-on:select="selectTest"
                    :exclude-ids="excludeIds"
                />
                <CListGroup>
                    <template
                        v-if="!!selectedTests.length"
                    >
                        <CListGroupItem
                            v-for="test in selectedTests"
                            :key="test.id"
                            class="d-flex justify-content-between align-items-center"
                        >
                            {{ test.title }}
                            <span
                                style="cursor: pointer"
                                @click="unselectTest(test)"
                            >
                                <CIcon
                                    name="cil-x"
                                />
                            </span>
                        </CListGroupItem>
                    </template>
                    <template v-else>
                        <CListGroupItem color="light">
                            Тесты не выбраны
                        </CListGroupItem>
                    </template>
                </CListGroup>
            </div>
            <template slot="footer">
                <CButton
                    color="secondary"
                    @click="isAddTestModalShown = false"
                >
                    Отмена
                </CButton>
                <CButton
                    color="primary"
                    @click="addTests()"
                    :disabled="!selectedTests.length"
                >
                    Добавить
                </CButton>
            </template>
        </CModal>
        <CModal
            :title="confirmTitle"
            :show.sync="isConfirmShown"
        >
            {{ confirmQuestion }}
            <template slot="footer">
                <CButton
                    color="secondary"
                    @click="isConfirmShown = false"
                >
                    Отмена
                </CButton>
                <CButton
                    color="primary"
                    @click="confirmCB"
                >
                    Ок
                </CButton>
            </template>
        </CModal>
        <CCol col="12" lg="6">
            <CCard>
                <CCardHeader>
                    Тестируемый #{{ $route.params.id }}
                    <CButton
                        color="primary"
                        class="float-right"
                        @click="editTestSubject()"
                    >
                        Редактировать
                    </CButton>
                </CCardHeader>
                <CCardBody>
                    <CDataTable
                        striped
                        small
                        fixed
                        :items="items"
                        :fields="fields"
                        :header="false"
                    >
                        <template slot="key" slot-scope="data">
                            <td>
                                {{ fieldAlias(data.item.key) }}
                            </td>
                        </template>
                        <template slot="value" slot-scope="data">
                            <td>
                                <template v-if="data.item.key === 'auth_code'">
                                    <strong>{{ data.item.value }}</strong>
                                    <CButtonGroup size="sm">
                                        <template
                                            v-if="data.item.value"
                                        >
                                            <CButton
                                                color="secondary"
                                                @click="confirmGenerateCode()"
                                                size="sm"
                                            >
                                                Сгенерировать новый код
                                            </CButton>
                                            <CButton
                                                v-if="test_subject.email"
                                                color="secondary"
                                                @click="sendInvite()"
                                                size="sm"
                                                :disabled="sendInviteLoading"
                                            >
                                                <CSpinner
                                                    color="info"
                                                    size="sm"
                                                    v-if="sendInviteLoading"
                                                />
                                                <template v-else>
                                                    Отправить на почту
                                                </template>
                                            </CButton>
                                        </template>
                                        <template v-else>
                                            <CButton
                                                color="primary"
                                                @click="generateCode()"
                                            >
                                                Сгенерировать
                                            </CButton>
                                        </template>
                                    </CButtonGroup>
                                </template>
                                <template v-else-if="data.item.key === 'auth_link'">
                                    <strong>{{ inviteLink }}</strong>
                                    <CButton
                                        v-if="windowIsSecureContext"
                                        color="primary"
                                        @click="copyCode"
                                        size="sm"
                                    >
                                        Скопировать
                                    </CButton>
                                </template>
                                <template v-else>
                                    <strong>{{ data.item.value }}</strong>
                                </template>
                            </td>
                        </template>
                    </CDataTable>
                </CCardBody>
                <CCardFooter>
                    <CButton color="primary" @click="goBack">Назад</CButton>
                </CCardFooter>
            </CCard>
            <CCard>
                <CCardHeader>
                    Тесты
                    <CButton
                        color="primary"
                        class="float-right"
                        @click="isAddTestModalShown = true; selectedTests = []"
                    >
                        Добавить
                    </CButton>
                </CCardHeader>
                <CCardBody>
                    <CDataTable
                        striped
                        small
                        fixed
                        :items="test_subject.tests"
                        :fields="testsFields"
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
                        <template slot="questions_count" slot-scope="{item}">
                            <td>
                                {{ item.test.questions_count }}
                            </td>
                        </template>
                        <template slot="passed" slot-scope="{item}">
                            <td>
                                {{ !!item.passed_at ? 'Пройден' : 'Не пройден' }}
                            </td>
                        </template>
                        <template #actions="{item}">
                            <td>
                                <template v-if="!!item.passed_at">
                                    <CButton
                                        color="secondary"
                                        @click="goToResult(item.id)"
                                    >
                                        Показать
                                    </CButton>
                                </template>
                                <template v-else>
                                    <CButton
                                        color="danger"
                                        @click="deleteTest(item)"
                                    >
                                        Удалить
                                    </CButton>
                                </template>
                            </td>
                        </template>
                    </CDataTable>
                </CCardBody>
                <CCardFooter>
                    <CButton color="primary" @click="goBack">Назад</CButton>
                </CCardFooter>
            </CCard>
        </CCol>
    </CRow>
</template>

<script>
import api from '@/api'
import TestSearch from "../../../components/TestSearch";

const FIELD_ALIASES = {
    id: '#',
    first_name: 'Имя',
    last_name: 'Фамилия',
    middle_name: 'Отчество',
    phone: 'Телефон',
    email: 'Email',
    auth_link: 'Уникальная ссылка',
    auth_code: 'Код доступа',
};

export default {
    name: 'TestSubject',
    components: {TestSearch},
    data: () => {
        return {
            test_subject: {},
            items: [],
            fields: [
                {key: 'key'},
                {key: 'value'},
            ],
            testsFields: [
                {key: 'id', label: '#'},
                {key: 'title', label: 'Название'},
                {key: 'description', label: 'Описание'},
                {key: 'passed', label: 'Пройден'},
                {key: 'actions', label: 'Действия'},
            ],
            isAddTestModalShown: false,
            selectedTests: [],
            messages: [],
            isConfirmShown: false,
            confirmTitle: '',
            confirmQuestion: '',
            confirmCB: () => {
            },
            sendInviteLoading: false
        }
    },
    methods: {
        editLink() {
            return `/admin/test-subjects/${this.$route.params.id.toString()}/edit`
        },
        editTestSubject() {
            const editLink = this.editLink();
            this.$router.push({path: editLink});
        },
        goToResult(userTestId) {
            this.$router.push({path: `/admin/test-subjects/${this.$route.params.id.toString()}/results/${userTestId}`});
        },
        goBack() {
            this.$router.go(-1)
        },
        fieldAlias(key) {
            return FIELD_ALIASES[key] || key;
        },
        parseData() {
            const items = Object.entries(this.test_subject);
            const keys = Object.keys(FIELD_ALIASES)
            this.items = items
                .filter(([key]) => keys.includes(key))
                .map(([key, value]) => ({key, value}));

            if (this.test_subject.auth_code) {
                this.items.push({key: 'auth_link'})
            }
        },
        load() {
            api.admin.testSubjects.show(this.$route.params.id)
                .then(response => {
                    this.test_subject = response.data.test_subject
                    this.parseData()
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.pushError(error)))
                    } else {
                        this.pushError('Что-то пошло не так')
                    }
                })
        },
        confirmGenerateCode() {
            return this.confirm(
                'Новая ссылка',
                'Вы уверены, что хотите сгенерировать новый код доступа? Старый будет недоступна',
                () => {
                    this.generateCode()
                }
            )
        },
        generateCode() {
            api.admin.testSubjects.generateCode(this.$route.params.id)
                .then(response => {
                    this.test_subject = response.data.test_subject
                    this.parseData()
                    this.pushMessage('Новая ссылка сгенерирована')
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.pushError(error)))
                    } else {
                        this.pushError('Что-то пошло не так')
                    }
                })
        },
        sendInvite() {
            this.sendInviteLoading = true
            api.admin.testSubjects.sendInvite(this.$route.params.id)
                .then(() => {
                    this.pushMessage('Новая ссылка сгенерирована')
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.pushError(error)))
                    } else {
                        this.pushError('Что-то пошло не так')
                    }
                })
                .finally(() => {
                    this.sendInviteLoading = false
                })
        },
        copyCode() {
            navigator.clipboard.writeText(this.inviteLink)
                .then(() => {
                    this.pushMessage('Ссылка скопирована в буфер обмена')
                })
                .catch(err => {
                    console.error(err);
                    this.pushError('Что-то пошло не так')
                });

        },
        selectTest(test) {
            this.selectedTests.push(test)
        },
        unselectTest(test) {
            this.selectedTests.splice(this.selectedTests.indexOf(test), 1);
        },
        addTests() {
            const testIds = this.selectedTests.map(test => test.id)
            api.admin.testSubjects.addTests(this.$route.params.id, {tests: testIds})
                .then(response => {
                    this.test_subject = response.data.test_subject
                    this.parseData()
                    this.selectedTests = []
                    this.isAddTestModalShown = false
                    this.pushMessage('Тест(ы) добавлен(ы)')
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.pushError(error)))
                    } else {
                        this.pushError('Что-то пошло не так')
                    }
                })
        },
        deleteTest(test) {
            api.admin.testSubjects.deleteTest(this.test_subject.id, test.id)
                .then(response => {
                    this.test_subject = response.data.test_subject
                    this.parseData()
                    this.pushMessage('Тест удален')
                })
                .catch(({status, data}) => {
                    if (status === 422) {
                        Object.values(data.errors).map(errors => errors.map(error => this.pushError(error)))
                    } else {
                        this.pushError('Что-то пошло не так')
                    }
                })
        },
        pushError(message) {
            this.messages.push({
                type: 'error',
                message
            })
        },
        pushMessage(message) {
            this.messages.push({
                message
            })
        },
        confirm(title, question, cb) {
            this.confirmTitle = title
            this.confirmQuestion = question
            this.confirmCB = () => {
                cb()
                this.isConfirmShown = false
            }
            this.isConfirmShown = true
        }
    },
    computed: {
        excludeIds() {
            return this.test_subject.tests && this.test_subject.tests.map(test => test.id).concat(this.selectedTests.map(test => test.id))
        },
        inviteLink() {
            return (process.env.MIX_APP_URL || 'http://localhost:8000') + `/#/login?phone=${this.test_subject.phone}&code=${this.test_subject.auth_code}`
        },
        windowIsSecureContext() {
            return window.isSecureContext
        },
    },
    mounted: function () {
        this.load();
    },
}
</script>
