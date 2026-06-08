const monthYear = document.getElementById("monthYear");
const grid = document.querySelector(".calendar-grid");
const prevBtn = document.getElementById("prevMonth");
const nextBtn = document.getElementById("nextMonth");
const bookingModal = document.getElementById("bookingModal");
const bookingClose = document.getElementById("bookingClose");
const bookingForm = document.getElementById("bookingForm");
const bookingDate = document.getElementById("bookingDate");
const bookingTime = document.getElementById("bookingTime");
const teacherSelect = document.getElementById("teacherId");
const bookingError = document.getElementById("bookingError");

let current = new Date();
let selectedDay = null;

const lessons = document.lessons || {};
const teachers = document.teachers || [];
const canBook = document.canBook === true;

function showBookingError(message) {
  bookingError.textContent = message;
}

function openBookingModal(date) {
  selectedDay = date;
  bookingDate.value = date;
  bookingTime.value = '17:00';
  teacherSelect.selectedIndex = 0;
  showBookingError('');
  bookingModal.style.display = 'flex';
  bookingModal.classList.remove('hidden');

  if (!canBook) {
    showBookingError('Tylko uczniowie mogą rezerwować lekcje.');
    return;
  }

  if (teachers.length === 0) {
    showBookingError('Brak dostępnych nauczycieli do wyboru.');
    return;
  }
}

function closeBookingModal() {
  bookingModal.classList.add('hidden');
  bookingModal.style.display = 'none';
  bookingDate.value = '';
  bookingTime.value = '';
  teacherSelect.selectedIndex = 0;
  selectedDay = null;
}

function populateTeacherSelect() {
  teacherSelect.innerHTML = '<option value="">Wybierz nauczyciela</option>';
  teachers.forEach(t => {
    const option = document.createElement('option');
    option.value = t.id;
    option.textContent = t.name;
    teacherSelect.appendChild(option);
  });
}

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
    day.addEventListener('click', () => openBookingModal(key));

    if(lessons[key]){
      lessons[key].forEach(eventData => {
        const item = document.createElement("div");
        item.className = "lesson-item";

        const l = document.createElement("div");
        l.className = "lesson";
        l.textContent = eventData.title;
        item.appendChild(l);

        const actions = document.createElement("div");
        actions.className = "actions";

        const cancel = document.createElement("a");
        cancel.href = "#";
        cancel.className = "cancel";
        cancel.innerHTML = "Odwołaj";
        cancel.addEventListener('click', async (event) => {
          event.stopPropagation();
          await fetch("calendar.php", {
            method: 'post',
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `uid=${eventData.uid}`,
          });
          deleteLessonInView(key, eventData.uid);
          renderCalendar(current);
        });
        actions.appendChild(cancel);

        const join = document.createElement("a");
        join.href = eventData.link;
        join.target = "_blank";
        join.className = "join";
        join.innerHTML = "Dołącz";
        join.addEventListener('click', event => event.stopPropagation());
        actions.appendChild(join);

        item.appendChild(actions);
        day.appendChild(item);
      });
    }

    grid.appendChild(day);
  }
}

bookingClose.onclick = closeBookingModal;
window.addEventListener('click', event => {
  if (event.target === bookingModal) {
    closeBookingModal();
  }
});

function addLessonInView(date, lesson) {
  if (!lessons[date]) {
    lessons[date] = [];
  }
  lessons[date].push(lesson);
}

function deleteLessonInView(date, uid) {
  if (!lessons[date]) {
    return;
  }
  lessons[date] = lessons[date].filter(event => event.uid !== uid);
  if (lessons[date].length === 0) {
    delete lessons[date];
  }
}

bookingForm.addEventListener('submit', async event => {
  event.preventDefault();

  const time = bookingTime.value.trim();
  const teacherId = teacherSelect.value;

  if (!selectedDay || !time || !teacherId) {
    showBookingError('Wypełnij godzinę i wybierz nauczyciela.');
    return;
  }

  const formData = new FormData();
  formData.append('action', 'create');
  formData.append('date', selectedDay);
  formData.append('time', time);
  formData.append('teacherId', teacherId);

  let result;
  try {
    const response = await fetch('calendar-action.php', {
      method: 'post',
      credentials: 'same-origin',
      body: formData,
    });

    const text = await response.text();
    if (!response.ok) {
      console.error('Server error', response.status, text);
      showBookingError('Błąd serwera. Spróbuj ponownie później.');
      return;
    }

    try {
      result = JSON.parse(text);
    } catch (parseError) {
      console.error('Invalid JSON response', text);
      showBookingError('Nieprawidłowa odpowiedź serwera. Spróbuj ponownie.');
      return;
    }
  } catch (error) {
    console.error(error);
    showBookingError('Błąd połączenia. Spróbuj ponownie później.');
    return;
  }

  if (!result.success) {
    showBookingError(result.error || 'Nie udało się zapisać lekcji.');
    return;
  }

  addLessonInView(selectedDay, result.lesson);
  renderCalendar(current);
  closeBookingModal();
});

populateTeacherSelect();
prevBtn.onclick = ()=>{ current.setMonth(current.getMonth()-1); renderCalendar(current); };
nextBtn.onclick = ()=>{ current.setMonth(current.getMonth()+1); renderCalendar(current); };

renderCalendar(current);
