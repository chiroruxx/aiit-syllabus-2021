<template>
    <form method="GET" class="my-16 pl-4">
        <div class="grid gap-y-4" style="grid-template-columns: 5rem 1fr;">
            <span>コース: </span>
            <div>
                <template v-for="course in courses">
                    <label class="inline-block mr-4">
                                            <input type="checkbox"
                                                   name="search[courses][]"
                                                   value="{{ course.value}}"
                                                   v-model="course.checked"
                                            >
                                            {{ course.label }}
                    </label>
                    <!--                           @if(in_array($course->getValue(), $selected['courses'], true)) checked="checked" @endif-->
                    <!--                    class="flex-none"-->
                </template>
            </div>
            <span>時期: </span>
            <div class="flex flex-row">
<!--                @foreach($quarters as $quarter)-->
                <label class="inline-block mr-4">
<!--                    <input type="checkbox"-->
<!--                           name="search[quarters][]"-->
<!--                           value="{{ $quarter}}"-->
<!--                           @if(in_array($quarter, $selected['quarters'], true)) checked="checked" @endif-->
<!--                    >-->
<!--                    {{ $quarter }}Q-->
                </label>
<!--                @endforeach-->
            </div>
            <span>人物像: </span>
            <div>
<!--                @foreach($modelTypes as $modelType)-->
                <label class="inline-block mr-4">
<!--                    <input type="checkbox"-->
<!--                           name="search[model][types][]"-->
<!--                           value="{{ $modelType->getValue()}}"-->
<!--                           @if(in_array($modelType->getValue(), $selected['model']['types'], true)) checked="checked" @endif-->
<!--                    >-->
<!--                    {{ $modelType->label() }}-->
                </label>
<!--                @endforeach-->
            </div>
        </div>
        <button type="submit" class="border rounded-md mt-8 px-4 py-2 bg-transparent">
<!--            @include('icons.search')-->
        </button>
    </form>
</template>

<script>
module.exports = {
    data() {
        return {
            courses: [{label: 'a', value: 0, checked: false}, {label: 'c', value: 1, checked: false}, {label: 'b', value: 5, checked: false}],
        }
    },

    mounted() {
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
            })
        }
    }
};
</script>
