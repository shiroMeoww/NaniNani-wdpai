const monthYear = document.getElementById("monthYear");
const grid = document.querySelector(".calendar-grid");
const prevBtn = document.getElementById("prevMonth");
const nextBtn = document.getElementById("nextMonth");

let current = new Date();

const lessons = document.lessons;

function renderCalendar(date){
  const year = date.getFullYear();
  const month = date.getMonth();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month+1, 0);

  monthYear.textContent = date.toLocaleDateString("pl-PL", {month:"long", year:"numeric"});
  document.querySelectorAll(".day").forEach(d=>d.remove());

  const start = (firstDay.getDay()+6)%7; 
  const total = lastDay.getDate();
  for(let i=0;i<start;i++){
    const empty = document.createElement("div");
    grid.appendChild(empty);
  }

  for(let d=1; d<=total; d++){
    const day = document.createElement("div");
    day.className = "day";

    const num = document.createElement("div");
    num.className = "day-number";
    num.textContent = d;
    day.appendChild(num);

    const key = `${year}-${String(month+1).padStart(2,"0")}-${String(d).padStart(2,"0")}`;
    if(lessons[key]){
      const l = document.createElement("div");
      l.className = "lesson";
      l.textContent = lessons[key].title;
      day.appendChild(l);
      const actions = document.createElement("div");
      actions.className = "actions";
      const cancel = document.createElement("a");
      cancel.href = "#";
      cancel.className = "cancel";
      cancel.innerHTML = "Odwołaj";
      cancel.addEventListener('click', async _ => {
        await fetch("calendar.php", {
          method: 'post',
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `uid=${lessons[key].uid}`,
        });
        l.remove();
        actions.remove();
      })
      actions.appendChild(cancel);
      const join = document.createElement("a");
      join.href = lessons[key].link;
      join.target = "_blank";
      join.className = "join";
      join.innerHTML = "Dołącz";
      actions.appendChild(join);
      day.appendChild(actions);
    }

    grid.appendChild(day);
  }
}

prevBtn.onclick = ()=>{ current.setMonth(current.getMonth()-1); renderCalendar(current); };
nextBtn.onclick = ()=>{ current.setMonth(current.getMonth()+1); renderCalendar(current); };

renderCalendar(current);
