<template>
    <div class="left-calendar">
        <VCalendar :attributes='attributes'>
          </VCalendar>
    </div>
</template>

<script>
import useTasks from "../../composables/tasks.js"
import {onMounted} from "vue";

export default {
    setup() {
        const {tasks, getTasks, storeTask, isSamePage } = useTasks()
        onMounted(getTasks)

        return { tasks }
    },
    computed: {
        attributes() {
            if (!this.tasks.length) {
                return ;
            }

            const todos = []

            this.tasks.forEach((item, index) => {
                let date = new Date(item.start_date)
                let year = date.getFullYear()
                let month = date.getMonth()
                let day = date.getDate()

                todos.push({
                    description: item.title,
                    isComplete: true,
                    dates: new Date(year, month, day),
                    color: 'red',
                })
            })

            // return todos

            return [
                {
                    key: 'today',
                    highlight: true,
                    dates: new Date(),
                },
                // Attributes for todos
                ...todos.map(todo => ({
                dates: todo.dates,
                dot: {
                    color: todo.color,
                    class: todo.isComplete ? 'opacity-75' : '',
                },
                popover: {
                    label: todo.description,
                },
                customData: todo,
                })),
            ];
        },
    },
};

</script>

