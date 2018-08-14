**
v2.2.0 :
**

* Add new Nextopia Ajax version (Now 1.5.1 and 2.0)

**
v2.1.0 :
**

* Add Personas tracking feature on the extension 

**
v2.0.0 :
**

* Add CRON exporter feature on the extension 

**
v1.4.0 :
**

* Allow user to use Magento 2 widget on templates by adding this line of JS code in the control panel 
(http://merchant.nextopiasoftware.com/custom-ajax-code.php)

```
    nxtOptions.mageOptions = [ {
                    "type": "collapsible",
                    "selector": "#selector_id",
                    "params": {"openedState": "active", "collapsible": true, "active": false, "collateral": { "openedState": "filter-active", "element": "body" } }
                },{
                    "type": "accordion",
                    "selector": "#selector_id",
                    "params": {"openedState": "active", "collapsible": true, "active": false, "multipleCollapsible": false}
                }
    ];

```

**
Compatible widgets are :
**

* Accordion widget (type : ACCORDION)

* Alert widget (type : ALERT)

* Collapsible widget (type : COLLAPSIBLE)

* Confirmation widget (type : CONFIRM)

* Modal widget (type : MODAL)

* Prompt widget (type : PROMPT)


