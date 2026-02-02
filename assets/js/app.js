// assets/js/app.js

document.addEventListener("input", async (e) => {
  const el = e.target;
  if (!el.matches("[data-autocomplete='cars']")) return;

  const q = el.value.trim();
  const list = document.querySelector("#car-suggestions");
  if (!list) return;

  if (q.length < 2) {
    list.innerHTML = "";
    list.style.display = "none";
    return;
  }

  try {
    const res = await fetch(`public/ajax/autocomplete.php?q=${encodeURIComponent(q)}`);

    const data = await res.json();

    list.innerHTML = data.map(item => `
      <button type="button" class="chip" data-pick="${item}">
        ${item}
      </button>
    `).join("");

    list.style.display = "flex";
    list.style.gap = "8px";
    list.style.flexWrap = "wrap";
    list.style.marginTop = "10px";
  } catch (err) {
    // keep quiet
  }
});

document.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-pick]");
  if (!btn) return;
  const input = document.querySelector("[data-autocomplete='cars']");
  if (!input) return;
  input.value = btn.getAttribute("data-pick") || "";
  const list = document.querySelector("#car-suggestions");
  if (list) { list.innerHTML = ""; list.style.display = "none"; }
});
