var Chrome = VueColor.Chrome;
Vue.component('colorpicker', {
	components: {
		'chrome-picker': Chrome,
	},
	template: `
<div class="input-group color-picker" ref="colorpicker">
	<input type="text" :class="'form-control ' + inputclass" v-bind="$attrs" v-model="colorValue" @focus="showPicker()" @input="updateFromInput" />
	<span class="input-group-addon color-picker-container">
		<span class="current-color" :style="'background-color: ' + colorValue" @click="togglePicker()"></span>
		<chrome-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" />
	</span>
</div>`,
	props: ['color', 'value', 'inputclass'],
	data() {
		return {
			colors: {
				hex: '#000000',
			},
			colorValue: '',
			displayPicker: false,
		}
	},
	mounted() {
		this.setColor(this.value || '#222222');
	},
	methods: {
		setColor(color) {
			this.updateColors(color);
			this.colorValue = color;
		},
		updateColors(color) {
			if(color.slice(0, 1) == '#') {
				this.colors = {
					hex: color
				};
			}
			else if(color.slice(0, 4) == 'rgba') {
				var rgba = color.replace(/^rgba?\(|\s+|\)$/g,'').split(','),
					hex = '#' + ((1 << 24) + (parseInt(rgba[0]) << 16) + (parseInt(rgba[1]) << 8) + parseInt(rgba[2])).toString(16).slice(1);
				this.colors = {
					hex: hex,
					a: rgba[3],
				}
			}
		},
		showPicker() {
			document.addEventListener('click', this.documentClick);
			this.displayPicker = true;
		},
		hidePicker() {
			document.removeEventListener('click', this.documentClick);
			this.displayPicker = false;
		},
		togglePicker() {
			this.displayPicker ? this.hidePicker() : this.showPicker();
		},
		updateFromInput() {
			if(/^#[0-9A-F]{6}$/i.test(this.colorValue)) {
				this.updateColors(this.colorValue);
			} else {
				return false;
			}
		},
		updateFromPicker(color) {
			this.colors = color;
			if(color.rgba.a == 1) {
				this.colorValue = color.hex;
			}
			else {
				this.colorValue = 'rgba(' + color.rgba.r + ', ' + color.rgba.g + ', ' + color.rgba.b + ', ' + color.rgba.a + ')';
			}
		},
		documentClick(e) {
			try {
				var el = this.$refs.colorpicker,
					target = e.target;
				if(el !== target && !el.contains(target)) {
					this.hidePicker()
				}
			} catch($e) { return; }
		}
	},
	watch: {
		value(val) {
			this.colorValue = val;
		},
		colorValue(val) {
			this.updateColors(val);
			this.$emit('input', val);
		}
	},
});