<template>
    <div class="search-container">
        <CDropdown
            color="primary"
            toggler-text="Dropdown Button"
            class="m-2"
        >
            <template slot="toggler">
                <CInput
                    placeholder="Поиск"
                    v-model="searchText"
                    class="input"
                />
            </template>
            <template
                v-if="loading"
            >
                <CDropdownItem disabled>
                    <CSpinner color="info"/>
                </CDropdownItem>
            </template>
            <template
                v-else-if="tests.length"
            >
                <CDropdownItem
                    v-for="test in tests"
                    :key="test.id"
                    @click="select(test)">
                    {{ test.title }}
                </CDropdownItem>
            </template>
            <template v-else>
                <CDropdownItem
                    disabled
                >
                    {{ searchText ? 'Не найден не один тест' : 'Начните писать название теста' }}
                </CDropdownItem>
            </template>
        </CDropdown>

    </div>
</template>

<script>
import api from '@/api';

export default {
    name: 'TestSearch',
    props: {
        excludeIds: {
            Array,
            default: () => ([])
        }
    },
    data() {
        return {
            searchText: '',
            searchDelayTimer: false,
            loading: false,
            tests: []
        }
    },
    watch: {
        searchText() {
            this.search()
        }
    },
    mounted() {
        this.search()
    },
    methods: {
        search() {
            // delay search for stop typing
            this.loading = true
            clearTimeout(this.searchDelayTimer)
            this.searchDelayTimer = setTimeout(() => {
                this.getTests()
                    .then(() => {
                        this.loading = false
                    })
            }, 750)
        },
        getTests() {
            return api.admin.tests.search({search: this.searchText || undefined, exclude: this.excludeIds})
                .then(({body: {body}}) => {
                    this.tests = body
                })
        },
        select(test) {
            this.$emit('select', test)
            this.clear()
        },
        clear() {
            this.tests = []
            this.searchText = ''
        }
    },
}
</script>

<style scoped lang="scss">
.input {
    margin: -0.5rem;
    margin-bottom: 1rem;
}
</style>
