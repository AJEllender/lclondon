import Alert from './Alert';

class Alerts {
  constructor(alerts) {
    this.alerts = [];

    for (var i = 0; i < alerts.length; i++) {
      this.alerts.push(new Alert(alerts[i].message, alerts[i].type));
    }
  }

  add(message, type) {
    if (!type) {
      type = 'default';
    }

    this.alerts.push(new Alert(message, type));
  }
}

export { Alerts as default };
