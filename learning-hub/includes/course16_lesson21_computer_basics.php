<?php
declare(strict_types=1);

/**
 * Course 16 — Computer Basics Mastery
 * Lesson 21 — Wi-Fi Basics
 */
function lh_course16_lesson21_computer_basics_override(array $lesson, int $position): ?string
{
    if ((string)($lesson['slug'] ?? '') !== 'course-16-lesson-21') {
        return null;
    }

    return <<<'HTML'
<section class="lh-topic">
  <h2>📶 Wi-Fi क्या है?</h2>
  <p><strong>Wi-Fi</strong> एक wireless networking technology है जो laptop, phone, tablet, TV और दूसरे devices को बिना Ethernet cable के local network से जोड़ती है। घर में यही network आम तौर पर router/access point के जरिए Internet तक पहुँच देता है।</p>
  <div class="lh-callout"><strong>सबसे जरूरी बात:</strong> Wi-Fi और Internet एक ही चीज़ नहीं हैं। Wi-Fi device और router के बीच connection है; Internet उस router के बाहर की online service/network है।</div>
</section>

<section class="lh-topic">
  <h2>🏠 Router, Modem और Access Point</h2>
  <ul>
    <li><strong>Router:</strong> घर के network में devices के traffic को manage करता है और Internet connection share करता है।</li>
    <li><strong>Access Point:</strong> wireless devices को network से जोड़ने के लिए Wi-Fi signal देता है। कई home routers में access point built-in होता है।</li>
    <li><strong>Modem/ONT:</strong> ISP की connection technology को आपके home network के लिए usable connection में बदलता है। कई घरों में modem और router एक ही device में मिल जाते हैं।</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>📡 SSID और Wi-Fi Password</h2>
  <p><strong>SSID</strong> आपके wireless network का नाम है—जैसे <code>Home_WiFi</code>। जब आप Windows में available networks देखते हैं, तो यही नाम दिखाई देता है।</p>
  <p><strong>Password</strong> network में authentication के लिए इस्तेमाल होता है। सही SSID चुनना जरूरी है, खासकर तब जब आसपास कई समान नाम वाले networks हों।</p>
</section>

<section class="lh-topic">
  <h2>⚡ 2.4 GHz, 5 GHz और 6 GHz</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>Band</th><th>आम फायदा</th><th>ध्यान रखें</th></tr></thead>
    <tbody>
      <tr><td><strong>2.4 GHz</strong></td><td>लंबी range और दीवारों के पार अक्सर बेहतर coverage</td><td>अधिक interference और आम तौर पर कम throughput</td></tr>
      <tr><td><strong>5 GHz</strong></td><td>आम तौर पर ज्यादा speed और कम range congestion</td><td>दीवारों/दूरी का असर ज्यादा हो सकता है</td></tr>
      <tr><td><strong>6 GHz</strong></td><td>compatible modern devices में अतिरिक्त capacity और कम congestion</td><td>हर router/device इसे support नहीं करता और range सीमित हो सकती है</td></tr>
    </tbody>
  </table>
  <p>अगर router दो networks दिखाता है, तो सिर्फ “5 GHz = हमेशा बेहतर” मत मानिए। कमरे की दूरी, दीवारें, interference और device support के हिसाब से सही band बदल सकता है।</p>
</section>

<section class="lh-topic">
  <h2>🪟 Windows 11 में Wi-Fi से Connect करना</h2>
  <ol>
    <li>Taskbar पर <strong>Network / Sound / Battery</strong> area खोलें।</li>
    <li>Wi-Fi के सामने <strong>Manage Wi-Fi connections</strong> खोलें।</li>
    <li>जिस network को आप पहचानते और trust करते हैं उसे चुनें।</li>
    <li><strong>Connect</strong> दबाएँ और password डालें।</li>
    <li>जरूरत हो तो <strong>Connect automatically</strong> चुनें ताकि network range में आने पर Windows दोबारा connect कर सके।</li>
  </ol>
  <div class="lh-callout"><strong>Safety:</strong> केवल पहचान वाले या भरोसेमंद Wi-Fi network से connect करें। Public Wi-Fi में sensitive काम करते समय extra सावधानी रखें।</div>
</section>

<section class="lh-topic">
  <h2>📶 Signal Strength क्यों बदलती है?</h2>
  <p>Wi-Fi signal दूरी, दीवारों, furniture, metal objects और दूसरे wireless devices से प्रभावित हो सकती है। Router को बहुत बंद जगह या कमरे के एकदम कोने में रखने से coverage कमजोर हो सकती है।</p>
  <ul>
    <li>Router के पास जाकर signal compare करें।</li>
    <li>बहुत सारी दीवारों/बाधाओं के पीछे होने पर speed और stability घट सकती है।</li>
    <li>जरूरत हो तो router को अधिक central और ऊँची जगह पर रखने पर विचार करें।</li>
    <li>एक ही जगह पर बार-बार test करके ही निष्कर्ष निकालें।</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🚨 “Wi-Fi Connected” लेकिन Internet नहीं चल रहा</h2>
  <p>इसका मतलब अक्सर यह होता है कि PC router से जुड़ा है, लेकिन router/ISP से Internet तक communication में समस्या है। इसे step-by-step isolate करें:</p>
  <ol>
    <li>देखें कि Wi-Fi वास्तव में <strong>Connected</strong> दिखा रहा है।</li>
    <li>Airplane mode बंद है या नहीं जाँचें।</li>
    <li>दूसरे phone/laptop पर वही Wi-Fi चलाकर देखें।</li>
    <li>PC में Wi-Fi off/on करके दोबारा connect करें।</li>
    <li>Network को <strong>Forget</strong> करके password के साथ फिर से connect करें।</li>
    <li>जरूरत पड़ने पर modem/router को power cycle करें और पूरी तरह start होने का इंतजार करें।</li>
    <li>Windows का Network troubleshooter/Get Help diagnostics चलाएँ।</li>
    <li>अगर समस्या केवल PC में है, तो Wi-Fi adapter/driver और Windows updates जाँचें।</li>
  </ol>
</section>

<section class="lh-topic">
  <h2>🛠️ Common Wi-Fi Problems</h2>
  <table class="table table-bordered align-middle">
    <thead><tr><th>समस्या</th><th>पहला check</th></tr></thead>
    <tbody>
      <tr><td>Wi-Fi network दिखाई नहीं दे रहा</td><td>Wi-Fi on, Airplane mode off, router range और adapter check करें।</td></tr>
      <tr><td>Password गलत बता रहा है</td><td>सही SSID चुनें और password दोबारा ध्यान से डालें।</td></tr>
      <tr><td>बार-बार disconnect</td><td>Signal strength, दूरी, interference और driver/update check करें।</td></tr>
      <tr><td>Connected, no Internet</td><td>दूसरे device पर test करके तय करें कि समस्या PC में है या पूरे network/ISP में।</td></tr>
      <tr><td>बहुत slow speed</td><td>Router के पास test करें और 2.4/5 GHz के बीच तुलना करें।</td></tr>
    </tbody>
  </table>
</section>

<section class="lh-topic">
  <h2>🔐 Public Wi-Fi पर Safety</h2>
  <ul>
    <li>Network का नाम देखकर blindly trust न करें; official name verify करें।</li>
    <li>Banking, passwords और sensitive data के लिए unknown public network पर extra सावधानी रखें।</li>
    <li>जरूरत न हो तो file sharing/network discovery जैसी सुविधाएँ public networks पर खुली न छोड़ें।</li>
    <li>HTTPS वाली websites और trusted apps का इस्तेमाल करें।</li>
    <li>Wi-Fi password को public जगह पर खुले में share न करें।</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🎯 Practical Activity</h2>
  <p>अपने Windows PC पर यह छोटा exercise करें:</p>
  <ol>
    <li>Available Wi-Fi networks की list खोलें और अपने network का SSID पहचानें।</li>
    <li>Signal bars नोट करें।</li>
    <li>Router के पास जाकर signal फिर देखें।</li>
    <li>अगर 2.4 GHz और 5 GHz दोनों उपलब्ध हैं, तो दोनों से connect करके एक ही website पर speed/response की तुलना करें।</li>
    <li>Wi-Fi को disconnect करके फिर reconnect करें और देखें कि <strong>Connect automatically</strong> enabled होने पर क्या होता है।</li>
  </ol>
  <div class="lh-callout"><strong>Goal:</strong> सिर्फ “Wi-Fi चल रहा है” कहना नहीं—बल्कि signal, band, distance और Internet connection के बीच फर्क समझना है।</div>
</section>

<section class="lh-topic">
  <h2>❌ Common Mistakes</h2>
  <ul>
    <li>Wi-Fi connected देखकर मान लेना कि Internet भी जरूर चल रहा होगा।</li>
    <li>गलत या नकली SSID से connect हो जाना।</li>
    <li>हर slow connection को केवल “Internet slow” मान लेना।</li>
    <li>Router को बंद अलमारी, फर्श के कोने या भारी obstacles के पीछे रखना।</li>
    <li>समस्या होने पर बिना समझे network reset या driver uninstall कर देना।</li>
    <li>Unknown public Wi-Fi पर sensitive information इस्तेमाल करना।</li>
  </ul>
</section>

<section class="lh-topic">
  <h2>🧠 Quick Self-Check</h2>
  <ol>
    <li>Wi-Fi और Internet में क्या फर्क है?</li>
    <li>SSID क्या होता है?</li>
    <li>2.4 GHz और 5 GHz में एक मुख्य practical फर्क बताइए।</li>
    <li>“Connected, no Internet” में सबसे पहले दूसरे device से क्या test किया जा सकता है?</li>
    <li>Wi-Fi signal कमजोर हो तो दो आसान उपाय क्या हैं?</li>
  </ol>
  <details class="mt-3">
    <summary><strong>Answers देखें</strong></summary>
    <div class="lh-callout mt-3">
      <p><strong>1.</strong> Wi-Fi local wireless connection है; Internet external online network/services तक पहुँच है।</p>
      <p><strong>2.</strong> SSID Wi-Fi network का नाम है।</p>
      <p><strong>3.</strong> 2.4 GHz आम तौर पर ज्यादा range देता है, जबकि 5 GHz कई परिस्थितियों में ज्यादा throughput दे सकता है लेकिन range कम हो सकती है।</p>
      <p><strong>4.</strong> उसी Wi-Fi को दूसरे trusted device पर connect करके देखें।</p>
      <p><strong>5.</strong> Router के करीब जाएँ और obstacles/interference कम करें; जरूरत हो तो बेहतर placement पर विचार करें।</p>
    </div>
  </details>
</section>

<section class="lh-topic">
  <h2>🔑 Key Takeaway</h2>
  <p>अच्छा Wi-Fi troubleshooting करने का तरीका है <strong>problem को isolate करना</strong>: क्या PC Wi-Fi से जुड़ा है? क्या router reachable है? क्या दूसरे devices पर Internet चल रहा है? क्या signal/band/driver में समस्या है? इस सोच से random settings बदलने के बजाय आप जल्दी सही कारण तक पहुँच सकते हैं।</p>
</section>
HTML;
}
