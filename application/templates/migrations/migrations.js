var migrationInstanceVue = new Vue({
    el: '#migrationController',
    mixins: [mixVue],
    data: function() {
        return {
            backup: {
                filename: null
            },
            inputs: {
                old: '',
                new: home_url
            },
            message: {
                text: null,
                closable: false
            }
        }
    },
    methods: {
        async restoredb($file) {
            var confirm = await swal('Important! Are you sure you want to import this database? this can`t be undone. make sure you make a backup on this before taking this action.', {
                buttons: true,
                dangerMode: true,
                icon: 'warning'
            });
            if(!confirm) { return; }
            try {
                await this.showLoading('Importing Database...');
                await api.post(`/database/restore`, {
                    file: $file
                });
                swal('Your database has been successfully restored.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        async deletebackup($file) {
            var confirm = await swal('Are you sure you want to remove this? this can`t be undone. make sure you know what you are doing', {
                buttons: true,
                dangerMode: true,
                icon: 'warning'
            });
            if(!confirm) { return; }
            try {
                await this.showLoading('Please wait...');
                await api.delete(`/database/backup`, {
                    params: {
                        file: $file
                    }
                });
                await swal('Backup File has been removed.\nPage will automatically refresh after closing this message', { icon: 'success' });
                window.location.href = window.location.href;
            } catch($e) {
                this.backEnd($e);
            }
        },
        async exportdb() {
            try { console.log(this.backup.filename);
                await this.showLoading('Please wait...');
                await api.post(`/database/backup`, {
                    filename: this.backup.filename
                });
                await swal('Database has been exported successfully.\nPage will automatically refresh after closing this message', { icon: 'success' });
                window.location.href = window.location.href;
            } catch($e) {
                this.backEnd($e);
            }
        },
        async updateURLs() {
            var valid = await this.$validator.validate();
            if(!valid) return;
            try {
                await this.showLoading();
                await api.post(`/database/migrate`, this.inputs);
                swal('URLs has been updated.', { icon: 'success' });
            } catch($e) {
                this.backEnd($e);
            }
        },
        close() {
            this.message = { text: null, closable: false };
        }
    }
});