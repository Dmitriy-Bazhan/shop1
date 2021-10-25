import NewPluginFirstJs from './plugins/newplugin.plugin';

const PluginManager = window.PluginManager;

PluginManager.register('NewPluginFirstJs', NewPluginFirstJs);