(function(){
  const grid = document.getElementById('scheduleGrid');
  const days = ['Mon','Tue','Wed','Thu','Fri'];
  function render(schedule){
    grid.innerHTML = '';
    days.forEach(d=>{
      const col = document.createElement('div');
      const header = document.createElement('h4'); header.textContent = d; col.appendChild(header);
      const items = schedule.filter(s=>s.day.startsWith(d));
      if(items.length===0){
        const empty = document.createElement('div'); empty.className='schedule-slot'; empty.textContent='-'; col.appendChild(empty);
      } else {
        items.forEach(i=>{
          const s = document.createElement('div'); s.className='schedule-slot';
          s.innerHTML = `<strong>${i.code}</strong> <div>${i.title}</div><small>${i.start} - ${i.end}</small>`;
          col.appendChild(s);
        });
      }
      grid.appendChild(col);
    });
  }
  render(window.INIT_SCHEDULE || []);
})();
