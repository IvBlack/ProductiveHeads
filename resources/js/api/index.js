import Vue from 'vue';
import VueResource from 'vue-resource';

Vue.use(VueResource);

export default {
    auth: {
        logout() {
            return Vue.http.post('logout')
        },
        adminLogin(params) {
            return Vue.http.post('admin/login', params)
        },
        login(params) {
            return Vue.http.post('login', params)
        }
    },
    menu: {
        get(params) {
            return Vue.http.get('menu', {params})
        }
    },
    admin: {
        users: {
            load() {
                return Vue.http.get('admin/users')
            },
            show(id) {
                return Vue.http.get('admin/users/' + id)
            },
            update(id, params) {
                return Vue.http.put('admin/users/' + id, params)
            }
        },
        tests: {
            load(params) {
                return Vue.http.get('admin/tests', {params})
            },
            search(params) {
                return Vue.http.get('admin/tests/search', {params})
            },
            create(params) {
                return Vue.http.post('admin/tests', params)
            },
            update(id, params) {
                return Vue.http.post('admin/tests/' + id, params)
            },
            show(id) {
                return Vue.http.get('admin/tests/' + id)
            },
            delete(id) {
                return Vue.http.delete('admin/tests/' + id)
            },
            import(file) {
                let formData = new FormData();
                formData.append('file', file);
                return Vue.http.post('admin/tests/import', formData)
            }
        },
        testSubjects: {
            load(params) {
                return Vue.http.get('admin/test-subjects', {params})
            },
            create(params) {
                return Vue.http.post('admin/test-subjects', params)
            },
            update(id, params) {
                return Vue.http.post('admin/test-subjects/' + id, params)
            },
            show(id) {
                return Vue.http.get('admin/test-subjects/' + id)
            },
            delete(id) {
                return Vue.http.delete('admin/test-subjects/' + id)
            },
            generateCode(id) {
                return Vue.http.post('admin/test-subjects/' + id + '/generate-code')
            },
            addTests(id, test_ids) {
                return Vue.http.post('admin/test-subjects/' + id + '/add-tests', {test_ids})
            },
            deleteTest(id, test_id) {
                return Vue.http.delete(`admin/test-subjects/${id}/delete-test/${test_id}`)
            },
            showResult(testSubjectId, userTestId) {
                return Vue.http.get(`admin/test-subjects/${testSubjectId}/results/${userTestId}`)
            },
            sendInvite(testSubjectId) {
                return Vue.http.post(`admin/test-subjects/${testSubjectId}/send-invite`)
            }
        }
    },
    testSubject: {
        tests: {
            load(params) {
                return Vue.http.get('tests', {params})
            },
            show(id) {
                return Vue.http.get('tests/' + id)
            },
            answer(userTestId, questionId, answer_ids) {
                return Vue.http.put(`tests/${userTestId}/${questionId}`, {answer_ids})
            }
        }
    }
}
