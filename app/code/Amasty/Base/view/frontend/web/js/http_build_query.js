/**
 * PHP htp_build_query() analog
 */
define(
    [],
    function () {
        'use strict';

        /**
         * Encodes param according to RFC3986 standard.
         *
         * @param {string} str
         * @returns {string}
         */
        function encodeComponentRaw (str) {
            str = (str + '');
            return encodeURIComponent(str)
                .replace(/!/g, '%21')
                .replace(/'/g, '%27')
                .replace(/\(/g, '%28')
                .replace(/\)/g, '%29')
                .replace(/\*/g, '%2A');
        }

        /**
         * Encodes param according to RF1738 standard.
         *
         * @param {string} str
         * @returns {string}
         */
        function encodeComponent (str) {
            return encodeComponentRaw(str).replace(/%20/g, '+');
        }

        /**
         * Encode single GET param.
         *
         * @param {string} key
         * @param {string} val
         * @param {string} argSeparator
         * @param {function (string)} encodeFunc
         * @returns {string}
         */
        function buildParam (key, val, argSeparator, encodeFunc) {
            var result = [];
            if (val === true) {
                val = '1';
            } else if (val === false) {
                val = '0';
            }

            if (val !== null) {
                if (typeof val === 'object') {
                    for (var index in val) {
                        if (val[index] !== null) {
                            result.push(buildParam(key + '[' + index + ']', val[index], argSeparator, encodeFunc));
                        }
                    }

                    return result.join(argSeparator);
                } else if (typeof val !== 'function') {
                    return encodeFunc(key) + '=' + encodeFunc(val);
                } else {
                    throw new Error('There was an error processing for http_build_query().');
                }
            } else {
                return '';
            }
        };

        /**
         * Builds HTTP query in the same way as PHP htp_build_query() function.
         *
         * @param {array} formData
         * @param {string} numericPrefix
         * @param {string} argSeparator
         * @param {string} encType
         * @returns {string}
         */
        function httpBuildQuery (formData, numericPrefix, argSeparator, encType) {
            var result = [],
                encode = (encType == 'PHP_QUERY_RFC3986') ? encodeComponentRaw : encodeComponent;
            if (!argSeparator) {
                argSeparator = '&';
            }

            for (var key in formData) {
                if (numericPrefix && !isNaN(key)) {
                    key = String(numericPrefix) + key;
                }
                var query = buildParam(key, formData[key], argSeparator, encode);
                if (query !== '') {
                    result.push(query);
                }
            }

            return result.join(argSeparator);
        };

        return function (formData, numericPrefix, argSeparator, encType) {
            return httpBuildQuery(formData, numericPrefix, argSeparator, encType);
        }
    }
);
