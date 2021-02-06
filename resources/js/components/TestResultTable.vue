<template>
    <CDataTable
        hover
        striped
        :items="questions"
        :fields="fields"
    >
        <template #key="{index}">
            <td>{{ index + 1 }}</td>
        </template>
        <template slot="question" slot-scope="{item}">
            <td>
                <span v-html="item.question"></span>
                <template v-if="item.description">
                    <span v-html="item.description"></span>
                </template>
            </td>
        </template>
        <template slot="answer" slot-scope="{item}">
            <td>
                <CButtonGroup>
                    <CButton
                        disabled
                        v-for="answer in item.possible_answers"
                        v-bind:key="answer.id"
                        :color="item.answer_ids.includes(answer.id) ? 'primary' : 'light'"
                        v-html="answer.answer"
                    >
                    </CButton>
                </CButtonGroup>
            </td>
        </template>
    </CDataTable>
</template>

<script>
export default {
    name: "TestResultTable",
    props: {
        questions: {
            required: true,
            Array
        }
    },
    data() {
        return {
            fields: [
                {key: 'key', label: '#'},
                {key: 'question', label: 'Вопрос'},
                {key: 'answer', label: 'Ответ'},
            ],
        }
    }
}
</script>

<style scoped>

</style>
