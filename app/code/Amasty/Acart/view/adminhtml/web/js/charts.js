define([
    'jquery',
    'mage/translate',
    'Amasty_Acart/vendor/amcharts/core.min',
    'Amasty_Acart/vendor/amcharts/charts',
    'Amasty_Acart/vendor/amcharts/themes/animated'
], function ($, $t) {
    'use strict';

    $.widget('amasty_acart.Charts', {
        options: {
            data: {},
            rateInitSelector: 'amacart-rate-chart',
            revenueInitSelector: 'amacart-revenue-chart',
            efficiencyInitSelector: 'amacart-efficiency-chart'
        },

        /**
         *  Amcharts graphics initialization
         *
         *  @am4core - amcharts/core.js
         *  @am4charts - amcharts/charts.js
         *  @am4themes_animated - amcharts/animated.js
         */
        _create: function () {
            var self = this;

            self.renderRateChart();
            self.renderRevenueChart();
            self.renderEfficiencyChart();
        },

        renderRateChart: function () {
            var self = this,
                container = self.createContainer(self.options.rateInitSelector),
                chart = container.createChild(am4charts.PieChart),
                series = chart.series.push(new am4charts.PieSeries());

            am4core.useTheme(am4themes_animated);

            container.paddingBottom = 10;

            chart.width = am4core.percent(50);
            chart.hiddenState.properties.opacity = 0;
            chart.radius = am4core.percent(80);
            chart.innerRadius = am4core.percent(30);
            chart.startAngle = 180;
            chart.endAngle = 360;

            chart.data = [
                {
                    rateLabel: $t('Abandoned Carts'),
                    rateValue: self.options.data['rated-total'].replace('%', '')
                },
                {
                    rateLabel: $t('Orders'),
                    rateValue: 100 - self.options.data['rated-total'].replace('%', '')
                }
            ];

            series.dataFields.category = "rateLabel";
            series.dataFields.value = "rateValue";
            series.slices.template.cornerRadius = 3;
            series.slices.template.innerCornerRadius = 3;
            series.slices.template.stroke = am4core.color("#fff");
            series.slices.template.strokeWidth = 3;
            series.slices.template.inert = true;
            series.slices.template.tooltipText = "[bold]{category}:[/] [font-size:14px]{value}%";
            series.ticks.template.disabled = true;
            series.labels.template.disabled = true;
            series.alignLabels = false;
            series.legendSettings.labelText = "[#363636]{rateLabel}[/]";
            series.legendSettings.valueText = "[font-size: 20px #363636]{rateValue}%[/]";
            series.hiddenState.properties.startAngle = 180;
            series.hiddenState.properties.endAngle = 180;

            self.renderLegends(container, chart);
            chart.legend.labels.template.maxWidth = 150;
            chart.legend.labels.template.truncate = false;
            chart.legend.labels.template.wrap = true;
        },

        renderRevenueChart: function () {
            var self = this,
                container = self.createContainer(self.options.revenueInitSelector),
                chart = container.createChild(am4charts.XYChart),
                categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis()),
                valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            am4core.useTheme(am4themes_animated);

            container.paddingBottom = 10;
            container.paddingLeft = 5;

            chart.width = am4core.percent(35);
            chart.data = [{
                "type": "money",
                "value": 0
            }];

            categoryAxis.dataFields.category = "type";
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.disabled = true;

            valueAxis.renderer.labels.template.fill = "#363636";
            valueAxis.min = 0;

            self.renderSeries(chart,
                "money",
                self.options.data['potential-revenue'],
                '#d4d4d4',
                $t('Money awaiting in abandoned carts'));
            self.renderSeries(chart,
                "money",
                self.options.data['recovered-revenue'],
                '#2d93e2',
                $t('Money made of recovered carts'));

            self.renderLegends(container, chart);
        },

        renderEfficiencyChart: function () {
            var self = this,
                container = self.createContainer(self.options.efficiencyInitSelector),
                chart = container.createChild(am4charts.XYChart),
                categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis()),
                valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            am4core.useTheme(am4themes_animated);

            container.paddingBottom = 20;

            chart.width = am4core.percent(50);
            chart.data = [{
                "type": "count",
                "value": 0
            }];

            categoryAxis.dataFields.category = "type";
            categoryAxis.renderer.disabled = true;

            valueAxis.renderer.labels.template.fill = "#363636";
            valueAxis.min = 0;

            self.renderSeries(chart,
                "count",
                self.options.data['sent-total'],
                '#d4d4d4',
                $t('Sent emails'));
            self.renderSeries(chart,
                "count",
                self.options.data['restored-total'],
                '#2d93e2',
                $t('Recovered carts'));
            self.renderSeries(chart,
                "count",
                self.options.data['placed-total'],
                '#eb8124',
                $t('Actual purchases'));

            self.renderLegends(container, chart);
        },

        createContainer: function (id) {
            var container = am4core.create(id, am4core.Container);

            container.layout = "grid";
            container.fixedWidthGrid = false;
            container.width = am4core.percent(100);
            container.height = am4core.percent(100);

            return container;
        },

        renderSeries: function (chart, valueType, value, color, name) {
            var series = chart.series.push(new am4charts.ColumnSeries());

            series.name = $t(name);
            series.dataFields.valueY =  "value";
            series.dataFields.categoryX = "type";
            series.data = [{
                "type": valueType,
                "value": value
            }];
            series.sequencedInterpolation = true;
            series.columns.template.width = am4core.percent(100);
            series.contentWidth = 40;
            series.fill = am4core.color(color);
            series.strokeWidth = 0;
            series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{valueY}";
        },

        renderLegends: function (container, chart) {
            var legendContainer = container.createChild(am4core.Container),
                markerTemplate,
                marker;

            legendContainer.width = am4core.percent(50);
            legendContainer.valign = "middle";
            legendContainer.paddingLeft = 25;

            chart.legend = new am4charts.Legend();
            chart.legend.parent = legendContainer;
            chart.legend.labels.template.fill = "#363636";
            chart.legend.useDefaultMarker = true;

            markerTemplate = chart.legend.markers.template;
            markerTemplate.width = 10;
            markerTemplate.height = 10;

            marker = chart.legend.markers.template.children.getIndex(0);
            marker.cornerRadius(15, 15, 15, 15);
        }
    });

    return $.amasty_acart.Charts;
});
