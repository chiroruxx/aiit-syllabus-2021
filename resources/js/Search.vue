<template>
    <form method="GET" class="my-16 pl-4">
        <div class="grid gap-y-4" style="grid-template-columns: 5rem 1fr;">
            <span>コース: </span>
            <div>
                <template v-for="course in courses">
                    <label class="inline-block mr-4">
                        <input type="checkbox"
                               name="search[courses][]"
                               :value="course.value"
                               v-model="course.checked"
                        >
                        {{ course.label }}
                    </label>
                </template>
            </div>
            <span>時期: </span>
            <div>
                <template v-for="quarter in quarters">
                    <label class="inline-block mr-4">
                        <input type="checkbox"
                               name="search[quarters][]"
                               :value="quarter.value"
                               v-model="quarter.checked"
                        >
                        {{ quarter.label }}
                    </label>
                </template>
            </div>
            <span>人物像: </span>
            <div>
                <template v-for="modelType in modelTypes">
                    <label class="inline-block mr-4">
                        <input type="checkbox"
                           name="search[model][types][]"
                           :value="modelType.value"
                           v-model="modelType.checked"
                        >
                    {{ modelType.label }}
                    </label>
                </template>
            </div>
        </div>
        <button type="submit" class="border rounded-md mt-8 px-4 py-2 bg-transparent">
            <search-icon />
        </button>
    </form>
</template>

<script>
import axios from 'axios'
import SearchIcon from "./SearchIcon";
export default {
    components: {SearchIcon},
    data() {
        return {
            courses: [],
            quarters: [],
            modelTypes: [],
        }
    },

    async mounted() {
        await axios.get('/api/master')
        .then((response) => {
            this.courses = response.data.courses.map((course) => {
                course.checked = false
                return course
            })
            this.quarters = response.data.quarters.map(value => ({
                value,
                label: `${value}Q`,
                checked: false
            }))
            this.modelTypes = response.data.modelTypes.map((modelType) => {
                modelType.checked = false
                return modelType
            })
        })
        this.setDefaultValue()
    },

    methods: {
        setDefaultValue() {
            const searchParams = new URLSearchParams(location.search)
            searchParams.forEach((value, key) => {
                if (key === 'search[courses][]') {
                    this.courses = this.courses.map((course) => {
                        if (course.value !== parseInt(value)) {
                            return course
                        }

                        course.checked = true
                        return course
                    })
                }
                if (key === 'search[quarters][]') {
                    this.quarters = this.quarters.map((quarter) => {
                        if (quarter.value !== parseInt(value)) {
                            return quarter
                        }

                        quarter.checked = true
                        return quarter
                    })
                }
                if (key === 'search[model][types][]') {
                    this.modelTypes = this.modelTypes.map((modelType) => {
                        if (modelType.value !== parseInt(value)) {
                            return modelType
                        }

                        modelType.checked = true
                        return modelType
                    })
                }
            })

            if (!searchParams.has('search[courses][]')) {
                this.courses = this.courses.map((course) => {
                    course.checked = true
                    return course
                })
            }
            if (!searchParams.has('search[quarters][]')) {
                this.quarters = this.quarters.map((quarter) => {
                    quarter.checked = true
                    return quarter
                })
            }
            if (!searchParams.has('search[model][types][]')) {
                this.modelTypes = this.modelTypes.map((modelType) => {
                    modelType.checked = true
                    return modelType
                })
            }

        }
    }
};
</script>
