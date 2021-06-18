<template>
    <div class="mb-4">
        <label class="block">
            <input type="checkbox"
                   :name="parent.name"
                   :value="parent.value"
                   v-model="parent.checked"
                   @change="changedParent"
            >
            {{ parent.label }}
        </label>
        <div class="ml-4">
            <template v-for="child in children">
                <label class="inline-block mr-4">
                    <input type="checkbox"
                           :name="child.name"
                           :value="child.value"
                           v-model="child.checked"
                           @change="changedChild"
                    >
                    {{ child.label }}
                </label>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        parent: Object,
        children: Array
    },
    data() {
      return {
          parentChecked: this.parent.checked,
      }
    },
    computed: {
        isCheckedSomeChildren() {
            return this.children.some(child => child.checked)
        }
    },
    methods: {
        changedParent() {
            if (!this.parent.checked) {
                this.children.forEach((child) => {
                    child.checked = false
                })
            }
        },
        changedChild() {
            if (!this.parent.checked && this.isCheckedSomeChildren) {
                this.parent.checked = true
            }
        }
    }
}
</script>
