import BpaQcfForm from './plugins/bpa-qcf-form.plugin';

const PluginManager = window.PluginManager;

PluginManager.register('BpaQcfForm', BpaQcfForm, '[data-bpa-qcf-form]');
