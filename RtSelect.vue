<script>
import { Select } from "element-ui"
export default {
    extends: Select,
    name: "rt-select",
    methods: {
        setSelected() {
            if (!this.multiple) {
                let option = this.getOption(this.value);
                if (option.created) {
                    this.createdLabel = option.currentLabel;
                    this.createdSelected = true;
                } else {
                    this.createdSelected = false;
                }
                this.selectedLabel = option.currentLabel;
                this.selected = option;
                if (this.filterable) this.query = this.selectedLabel;
                if (option.created == undefined) {
                    this.selectedLabel = '';
                    this.selected = this.multiple ? [] : {};
                }
                return;
            }
            let result = [];
            if (Array.isArray(this.value)) {
                this.value.forEach(value => {
                    result.push(this.getOption(value));
                });
            }
            this.selected = result;
            this.$nextTick(() => {
                this.resetInputHeight();
            });
        },
    }
}
</script>