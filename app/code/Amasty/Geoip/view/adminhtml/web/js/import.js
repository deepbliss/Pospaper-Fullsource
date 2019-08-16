define([
    "jquery",
    "jquery/ui",
    "mage/translate"
], function ($) {

    $.widget('mage.amGeoip', {
        options: {},

        _create: function () {
            this.downloadStep = 1;
            this.urls = [];
            this.isDownload = false;
            if (this.options.type == 'download_n_import') {
                $('#am-download-import').click(function () {
                    for (var i = 0; i < this.options.importItems.length; i++) {
                        var item = this.options.importItems[i];
                        var startUrl = item.start;
                        var processUrl = item.process;
                        var commitUrl = item.commit;
                        var startDownloadingUrl = item.download;
                        this.runDownloading(startUrl, processUrl, commitUrl, startDownloadingUrl);
                    }
                }.bind(this));
            }
            if (this.options.type == 'import') {
                $('#am-import').click(function () {
                    for (var i = 0; i < this.options.importItems.length; i++) {
                        var item = this.options.importItems[i];
                        var startUrl = item.start;
                        var processUrl = item.process;
                        var commitUrl = item.commit;
                        this.run(startUrl, processUrl, commitUrl);
                    }
                }.bind(this));
            }
        },

        error: function (error, processer) {
            if (processer)
                $(processer.parentNode).remove();

        },

        done: function (response) {
            if (response.full_import_done == 1) {
                location.reload();
            }
        },

        runDownloading: function (startUrl, processUrl, commitUrl, startDownloadingUrl) {
            var _caller = this;
            var processer = $('.am_download');
            this.isDownload = true;

            processer[0].setStyle({
                'width': '30%'
            });

            if ($("#row_amgeoip_download_import_download_import_button .import .bubble").length) {
                $("#row_amgeoip_download_import_download_import_button .import .bubble")[0].innerHTML = '';
            }

            processer.find('span')[0].innerHTML = '0/3';

            if ($(".am_processer_container .end_downloading_completed").length) {
                $(".am_processer_container .end_downloading_completed")[0].addClassName('end_downloading_process');
                $(".am_processer_container .end_downloading_completed")[0].removeClassName('end_downloading_completed');
            }
            if ($(".am_processer_container .end_downloading_not_completed").length) {
                $(".am_processer_container .end_downloading_not_completed")[0].addClassName('end_downloading_process');
                $(".am_processer_container .end_downloading_not_completed")[0].removeClassName('end_downloading_not_completed');
            }

            $.ajax({
                url: startDownloadingUrl,
                type: 'POST',
                dataType: 'json',
                data: {form_key: FORM_KEY}
            }).done($.proxy(function (response) {

                if (response.status == 'finish_downloading') {
                    _caller.doneDownloading(startUrl, processUrl, commitUrl)
                } else if (response.status == 'done') {
                    processers = [$('.am_download'), $('.am_processer')];
                    processers.each(function (processer) {
                        processer.find('span')[0].innerHTML = '';
                        processer[0].setStyle({'width': '100%'});
                    });

                    if ($(".am_processer_container .end_not_imported").length) {
                        $(".am_processer_container .end_not_imported")[0].addClassName('end_processing');
                        $(".am_processer_container .end_not_imported")[0].removeClassName('end_not_imported');
                    }
                    if ($(".am_processer_container .end_downloading_process").length) {
                        $(".am_processer_container .end_downloading_process")[0].addClassName('end_downloading_completed');
                        $(".am_processer_container .end_downloading_process")[0].removeClassName('end_downloading_process');
                    }
                    if ($("#row_amgeoip_download_import_download_import_button .import .bubble").length) {
                        $("#row_amgeoip_download_import_download_import_button .import .bubble")[0].innerHTML = $.mage.__('GeoIp data is up-to-date.');
                        $("#row_amgeoip_download_import_download_import_button .import .bubble")[0].setStyle({
                            'color': 'red'
                        });
                    }

                } else if (response.error) {
                    if ($(".am_processer_container .end_downloading_process").length) {
                        $(".am_processer_container .end_downloading_process")[0].addClassName('end_downloading_not_completed');
                        $(".am_processer_container .end_downloading_process")[0].removeClassName('end_downloading_process');
                    }

                    processer.find('span')[0].innerHTML = '';

                    if ($("#row_amgeoip_download_import_download_import_button .import .bubble").length) {
                        $("#row_amgeoip_download_import_download_import_button .import .bubble")[0].innerHTML = 'Error';
                    }

                    processer[0].setStyle({
                        'width': '0%'
                    });
                    alert(response.error);
                    _caller.error(response.error);
                }

            }, this));
        },

        doneDownloading: function (startUrl, processUrl, commitUrl) {
            var _caller = this;
            var processer = $('.am_download');

            if (_caller.downloadStep == 3) {
                processer[0].setStyle({
                    'width': '100%'
                });
                processer.find('span')[0].innerHTML = '';

                if ($(".am_processer_container .end_downloading_process").length) {
                    $(".am_processer_container .end_downloading_process")[0].addClassName('end_downloading_completed');
                    $(".am_processer_container .end_downloading_process")[0].removeClassName('end_downloading_process');
                }

                this.urls.each(function(type) {
                    _caller.run(type[0], type[1], type[2])
                });
                _caller.run(startUrl, processUrl, commitUrl);
            } else {
                processer[0].setStyle({
                    'width': ((_caller.downloadStep) * 30) + '%'
                });

                processer.find('span')[0].innerHTML = _caller.downloadStep + '/3';

                this.urls.push([startUrl, processUrl, commitUrl]);

                _caller.downloadStep++;
            }
        },

        run: function (startUrl, processUrl, commitUrl) {
            var _caller = this;

            $.ajax({
                url: startUrl,
                type: 'POST',
                dataType: 'json',
                data: {form_key: FORM_KEY, is_download: _caller.isDownload}
            }).done($.proxy(function (response) {

                if ($(".am_processer_container .end_imported").length) {
                    $(".am_processer_container .end_imported")[0].addClassName('end_processing');
                    $(".am_processer_container .end_imported")[0].removeClassName('end_imported');
                }

                if ($(".am_processer_container .end_not_imported").length) {
                    $(".am_processer_container .end_not_imported")[0].addClassName('end_processing');
                    $(".am_processer_container .end_not_imported")[0].removeClassName('end_not_imported');
                }

                var processer = $('div.am_processer');

                if (response.status == 'started') {

                    _caller.process(processUrl, commitUrl, processer);

                } else if (response.error) {
                    if ($(".am_processer_container .end_processing").length) {
                        $(".am_processer_container .end_processing")[0].addClassName('end_not_imported');
                        $(".am_processer_container .end_processing")[0].removeClassName('end_processing');
                    }

                    $(".completed_import .bubble").innerHTML = 'Error';
                    $(".completed .bubble").innerHTML = 'Error';

                    $.each(processer, function (i, d) {
                        d.setStyle({'width': '0%'});
                    });

                    alert(response.error);
                    _caller.error(response.error);
                }

            }, this));
        },

        process: function (processUrl, commitUrl, processer) {
            var _caller = this;

            $.ajax({
                url: processUrl,
                type: 'POST',
                dataType: 'json',
                data: {form_key: FORM_KEY}
            }).done($.proxy(function (response) {
                if (response.status == 'processing') {

                    if (response.type == 'block') {
                        _caller.tracePosition(response.position, processer);
                    }

                    if (response.position == 100) {
                        _caller.commit(commitUrl, processer);
                    } else {
                        _caller.process(processUrl, commitUrl, processer);
                    }


                } else if (response.error) {
                    if ($(".am_processer_container .end_processing").length) {
                        $(".am_processer_container .end_processing")[0].addClassName('end_not_imported');
                        $(".am_processer_container .end_processing")[0].removeClassName('end_processing');
                    }

                    $(".completed_import .bubble").innerHTML = 'Error';
                    $(".completed .bubble").innerHTML = 'Error';

                    $.each(processer, function (i, d) {
                        d.setStyle({'width': '0%'});
                    });

                    _caller.error(response.error, processer);
                }
            }));
        },

        tracePosition: function (position, processer) {
            $.each(processer, function (i, d) {
                d.setStyle({'width': position + '%'});
            });
            $.each(processer, function (i, d) {
                $(d).find('span')[0].innerHTML = position + '%';
            });
        },

        commit: function (commitUrl, processer) {
            var _caller = this;

            $.ajax({
                url: commitUrl,
                type: 'POST',
                dataType: 'json',
                data: {form_key: FORM_KEY}
            }).done($.proxy(function (response) {
                if (response.status == 'done') {
                    _caller.done(response)
                } else if (response.error) {
                    _caller.error(response.error, processer);
                }
            }));
        },
    });
    return $.mage.amGeoip;
});
