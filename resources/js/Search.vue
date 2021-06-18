<template>
    <form method="GET" class="my-16 pl-4">
        <div class="grid gap-y-4" style="grid-template-columns: 5rem 1fr;">
            <span>コース: </span>
            <div>
                <template v-for="course in courses">
                    <check-box-group
                        :parent="course"
                        :children="course.models"
                    />
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
        </div>
        <button type="submit" class="border rounded-md mt-8 px-4 py-2 bg-transparent">
            <search-icon />
        </button>
    </form>
</template>

<script>
import axios from 'axios'
import SearchIcon from "./SearchIcon";
import CheckBoxGroup from "./CheckBoxGroup";

export default {
    components: {CheckBoxGroup, SearchIcon},
    data() {
        return {
            courses: [],
            quarters: [],
        }
    },

    async mounted() {
        await axios.get('/api/master')
        .then((response) => {
            this.courses = response.data.courses.map((course) => {
                course.checked = false
                course.name = 'search[courses][]'
                course.models.map((model) => {
                    model.checked = false
                    model.name = 'search[model][types][]'
                    return model
                })
                return course
            })
            this.quarters = response.data.quarters.map(value => ({
                value,
                label: `${value}Q`,
                checked: false
            }))
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
                    this.courses.forEach((course) => {
                        course.models.forEach((model) => {
                            if (model.value !== parseInt(value)) {
                                return
                            }

                            model.checked = true
                        })
                    })
                }
            })
        }
    }
};
</script>
