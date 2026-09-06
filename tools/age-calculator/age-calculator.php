<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Age Calculator Online — Calculate Exact Age',
    'description' => 'Calculate your exact age in years, months and days, plus total days, weeks and your next birthday with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/age-calculator/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="age-page">
  <section class="age-head">
    <span class="eyebrow">AGE CALCULATOR</span>
    <h1>Calculate your exact age</h1>
    <p>Enter your date of birth and a date to instantly calculate your age in years, months and days.</p>
  </section>

  <section class="age-card" aria-label="Age calculator">
    <div class="date-grid">
      <label>Date of birth<input id="dob" type="date" autocomplete="bday"></label>
      <label>Calculate age on<input id="asOf" type="date"></label>
    </div>
    <button class="calculate-button" id="calculate" type="button">Calculate Age</button>
    <button class="today-button" id="today" type="button">Use today</button>
    <p class="status" id="status" aria-live="polite"></p>

    <div class="results" id="results" hidden>
      <div class="main-result"><strong id="ageYmd">—</strong><span>years, months and days</span></div>
      <div class="result-grid">
        <div><strong id="totalMonths">—</strong><span>Total months</span></div>
        <div><strong id="totalWeeks">—</strong><span>Total weeks</span></div>
        <div><strong id="totalDays">—</strong><span>Total days</span></div>
        <div><strong id="nextBirthday">—</strong><span>Next birthday</span></div>
      </div>
    </div>
  </section>

  <section class="help-grid">
    <article><h2>How it works</h2><ol><li>Choose your date of birth.</li><li>Choose the date to calculate your age on, or use today.</li><li>Click Calculate Age to see the exact result.</li></ol></article>
    <article><h2>What you get</h2><p>See your exact age plus total months, weeks and days lived. The calculator also shows how many days remain until your next birthday.</p></article>
  </section>
</main>

<style>
.age-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.age-head{text-align:center;padding:48px 0 24px}.age-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.age-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.age-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.date-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.date-grid label{display:flex;flex-direction:column;gap:7px;color:#344054;font-size:11px;font-weight:800}.date-grid input{height:48px;width:100%;padding:0 13px;border:1px solid #dfe3eb;border-radius:12px;background:#fcfdff;color:#172033;font:13px Arial,sans-serif;outline:0}.date-grid input:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.calculate-button{width:calc(100% - 110px);height:50px;margin-top:16px;border:0;border-radius:13px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:13px;font-weight:900;cursor:pointer}.today-button{height:50px;margin-left:8px;padding:0 16px;border:1px solid #dfe3eb;border-radius:13px;background:#fff;color:#5541ff;font-size:11px;font-weight:800;cursor:pointer}.status{min-height:18px;margin:9px 0 0;text-align:center;color:#69738e;font-size:10px}.status.error{color:#c13232}.results{margin-top:14px;padding:20px;border:1px solid #ddd9ff;border-radius:17px;background:#f8f7ff}.main-result{text-align:center}.main-result strong{display:block;font-size:32px;letter-spacing:-1.2px}.main-result span{color:#667085;font-size:11px}.result-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:9px;margin-top:17px}.result-grid div{padding:14px 8px;border:1px solid #e5e2ff;border-radius:12px;background:#fff;text-align:center}.result-grid strong{display:block;font-size:17px}.result-grid span{display:block;margin-top:3px;color:#7b849d;font-size:9px}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.age-page{width:calc(100% - 20px)}.age-card{padding:16px}.date-grid{grid-template-columns:1fr}.calculate-button{width:100%}.today-button{width:100%;margin:8px 0 0}.result-grid{grid-template-columns:repeat(2,1fr)}.help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const dob=document.getElementById('dob'),asOf=document.getElementById('asOf'),calculate=document.getElementById('calculate'),today=document.getElementById('today'),status=document.getElementById('status'),results=document.getElementById('results'),ageYmd=document.getElementById('ageYmd'),totalMonths=document.getElementById('totalMonths'),totalWeeks=document.getElementById('totalWeeks'),totalDays=document.getElementById('totalDays'),nextBirthday=document.getElementById('nextBirthday');
 const pad=n=>String(n).padStart(2,'0');
 const localISO=d=>d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate());
 const parseDate=s=>{if(!/^\d{4}-\d{2}-\d{2}$/.test(s))return null;const [y,m,d]=s.split('-').map(Number),x=new Date(y,m-1,d);return x.getFullYear()===y&&x.getMonth()===m-1&&x.getDate()===d?x:null};
 const daysBetween=(a,b)=>Math.floor((Date.UTC(b.getFullYear(),b.getMonth(),b.getDate())-Date.UTC(a.getFullYear(),a.getMonth(),a.getDate()))/86400000);
 const calculateAge=()=>{
   const birth=parseDate(dob.value),end=parseDate(asOf.value);
   status.className='status';results.hidden=true;
   if(!birth||!end){status.className='status error';status.textContent='Please select both dates.';return}
   if(end<birth){status.className='status error';status.textContent='The calculation date cannot be before the date of birth.';return}
   let years=end.getFullYear()-birth.getFullYear();let months=end.getMonth()-birth.getMonth();let days=end.getDate()-birth.getDate();
   if(days<0){months--;const lastDay=new Date(end.getFullYear(),end.getMonth(),0).getDate();days+=lastDay}
   if(months<0){years--;months+=12}
   const total=daysBetween(birth,end);
   ageYmd.textContent=years+'y '+months+'m '+days+'d';totalMonths.textContent=(years*12+months).toLocaleString();totalWeeks.textContent=Math.floor(total/7).toLocaleString();totalDays.textContent=total.toLocaleString();
   let next=new Date(end.getFullYear(),birth.getMonth(),birth.getDate());if(next<end)next.setFullYear(end.getFullYear()+1);const until=daysBetween(end,next);nextBirthday.textContent=until===0?'Today':until.toLocaleString()+' days';
   results.hidden=false;status.textContent='Age calculated successfully.';
 };
 const now=new Date();asOf.value=localISO(now);today.addEventListener('click',()=>{asOf.value=localISO(new Date());calculateAge()});calculate.addEventListener('click',calculateAge);[dob,asOf].forEach(el=>el.addEventListener('change',()=>{if(dob.value&&asOf.value)calculateAge()}));
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
