console.log("Docflow filecontextmenuplugin.js loaded");

var isSecureViewerAvailable = function () {
    return $('#hideDownload').val() === 'true' && typeof OCA.RichDocuments !== 'undefined';
};

(function(OCA) {

    OCA.Docflow = OCA.Docflow || {};

    /**
     * @namespace OCA.Docflow.FilesContextMenuPlugin
     */
    OCA.Docflow.FilesContextMenuPlugin = {

        /**
         * @param fileList
         */
        attach: function(fileList) {
            this._extendFileActions(fileList.fileActions);
        },

        /**
         * @param fileName
         * @param context
         */
        redirect: function(fileName, context) {
            const _self = this;
            console.log("context.fileInfoModel.id", context.fileInfoModel.id);
            let appUrl = OC.generateUrl('/apps/docflow?fileId={fileId}', {fileId: context.fileInfoModel.id});
            console.log('appUrl', appUrl);
            window.location.replace(appUrl);
        },

        /**
         * @param fileActions
         * @private
         */
        _extendFileActions: function(fileActions) {
            var self = this;
            if (isSecureViewerAvailable()) {
                return;
            }
            fileActions.registerAction({
                name: 'docflow-elaborate',
                displayName: t('docflow', 'Process with Docflow'),
                iconClass: 'icon-category-workflow',
                mime: 'text/markdown',
                permissions: OC.PERMISSION_READ,
                actionHandler: function(fileName, context) {
                    console.log("docflow-elaborate clicked");
                    console.log("context", context);
                    //console.log("context.file", context.fileActions.currentFile);
                    self.redirect(fileName, context);
                    /* let downloadUrl = context.fileList.getDownloadUrl(fileName, context.dir);
                    if (downloadUrl && downloadUrl !== '#') {
                        let completeDownloadUrl = window.location.protocol + "//" + window.location.host + downloadUrl;
                        //console.log('completeDownloadUrl', completeDownloadUrl);
                        
                        //self.redirect(fileName, completeDownloadUrl, '', true, context);
                    } */
                }
            });
        }

    };

})(OCA);

OC.Plugins.register('OCA.Files.FileList', OCA.Docflow.FilesContextMenuPlugin);

