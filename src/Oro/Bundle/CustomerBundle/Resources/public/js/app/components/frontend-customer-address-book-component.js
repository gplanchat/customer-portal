/*jslint nomen:true*/
/*global define*/
define(function(require) {
    'use strict';

    var CustomerAddressBook;
    var BaseComponent = require('oroui/js/app/components/base/component');
    var _ = require('underscore');
    var routing = require('routing');
    var AddressBook = require('orocustomer/js/address-book');
    var deleteConfirmation = require('oroui/js/delete-confirmation');

    CustomerAddressBook = BaseComponent.extend({
        /**
         * @property {Object}
         */
        defaultOptions: {
            'entityId': null,
            'addressListUrl': null,
            'addressCreateUrl': null,
            'addressUpdateRouteName': null,
            'addressDeleteRouteName': null,
            'currentAddresses': [],
            'useFormDialog': false,
            'template': '',
            'manageAddressesLink': '',
            'enableMapPreview': true
        },

        /**
         * @param {Object} options
         */
        initialize: function(options) {
            options = _.defaults(options || {}, this.defaultOptions);

            /** @type oroaddress.AddressBook */
            var addressBook = new AddressBook({
                el: options._sourceElement.get(0),
                template: options.template,
                manageAddressesLink: options.manageAddressesLink,
                addressListUrl: options.addressListUrl,
                addressCreateUrl: options.addressCreateUrl,
                addressUpdateUrl: function() {
                    var address = arguments[0];
                    return routing.generate(
                        options.addressUpdateRouteName,
                        {'id': address.get('id'), 'entityId': options.entityId}
                    );
                },
                addressDeleteUrl: function() {
                    var address = arguments[0];
                    return routing.generate(
                        options.addressDeleteRouteName,
                        {'addressId': address.get('id'), 'entityId': options.entityId}
                    );
                },
                addressMapOptions: {'phone': 'phone'},
                useFormDialog: options.useFormDialog,
                mapViewport: options.mapViewport,
                allowToRemovePrimary: true,
                confirmRemove: true,
                confirmRemoveComponent: deleteConfirmation,
                enableMapPreview: options.enableMapPreview
            });

            addressBook.getCollection().reset(JSON.parse(options.currentAddresses));
            options._sourceElement.children('.view-loading').remove();
        }
    });

    return CustomerAddressBook;
});
