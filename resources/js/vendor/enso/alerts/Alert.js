class Alert {
    constructor(message, type) {
        if (!type) {
            type = 'default';
        }

        this.message = message;
        this.type = type;
    }

    getClasses() {
        const classes = {
            error:   'is-danger',
            info:    'is-info',
            success: 'is-success',
            primary: 'is-primary',
            warning: 'is-warning',
        };

        return 'notification ' + (classes[this.type] || 'default');
    }
}

export { Alert as default };