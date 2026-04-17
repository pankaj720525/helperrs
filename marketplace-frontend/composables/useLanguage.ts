/**
 * Language composable — supports English, Hindi, and Gujarati.
 * Persists preference in localStorage.
 */

export type Lang = 'en' | 'hi' | 'gu';

export interface LangOption {
  code: Lang;
  label: string;
  nativeLabel: string;
  flag: string;
}

export const LANGUAGES: LangOption[] = [
  { code: 'en', label: 'English',  nativeLabel: 'English', flag: '🇬🇧' },
  { code: 'hi', label: 'Hindi',    nativeLabel: 'हिन्दी',  flag: '🇮🇳' },
  { code: 'gu', label: 'Gujarati', nativeLabel: 'ગુજરાતી', flag: '🇮🇳' },
];

/* ── Translation dictionary ─────────────────────────────── */
const translations: Record<Lang, Record<string, string>> = {
  en: {
    home: 'Home',
    services: 'Services',
    search: 'Search',
    dashboard: 'Dashboard',
    chats: 'Chats',
    login: 'Login',
    register: 'Register',
    logout: 'Logout',
    findService: 'Find a Service',
    searchPlaceholder: 'Search services...',
    heroTitle: 'Find Trusted Local Professionals',
    heroSubtitle: 'Connect with skilled service providers near you — fast, reliable, and vetted.',
    bookNow: 'Book Now',
    viewAll: 'View All Services',
    featuredServices: 'Featured Services',
    howItWorks: 'How It Works',
    topProviders: 'Top Providers',
    allCategories: 'All Categories',
    findServices: '🔍 Find Services',
    browseCategory: 'Browse by Category',
    browseCategorySub: 'Choose from our wide range of professional services',
    howItWorksSteps: 'Get started in 3 simple steps',
    ctaTitle: 'Are You a Service Professional?',
    ctaSubtitle: 'Join our marketplace and start connecting with customers in your area. Get a free trial period to get started.',
    registerAsWorker: 'Register as Worker →',
    searchByLocation: 'Search by Location',
    findNearYou: 'Find service professionals near you',
    category: 'Category',
    radius: 'Radius (km)',
    keyword: 'Keyword',
    keywordPlaceholder: 'e.g. AC repair',
    searching: 'Searching...',
    myLocation: '📍 My Location',
    viewDetails: 'View Details',
    noResults: 'No services found nearby. Try increasing the search radius.',
    servicesCount: 'services',
    stepSearchTitle: 'Search',
    stepSearchDesc: 'Browse categories or search by location to find the right professional.',
    stepConnectTitle: 'Connect',
    stepConnectDesc: 'Chat directly with service providers and discuss your requirements.',
    stepReviewTitle: 'Review',
    stepReviewDesc: 'Leave a review after the job is done to help the community.',
    priceOnRequest: 'Price on request',
    noServicesFound: 'No services found. Check back soon!',
    previous: 'Previous',
    next: 'Next',
    locationInfoMsg: 'Services are linked to the worker\'s profile location. Workers set their area when setting up their profile — the search finds services offered by workers near your chosen area.',
    searchArea: 'Your Area / Location',
    areaPlaceholder: 'e.g. Ahmedabad, Vastrapur, Maninagar...',
    findArea: 'Find',
    usingLocation: 'Searching near',
    areaHidden: 'Location not shared',
    resultsNear: 'results near',
    noResultsTip: 'Try increasing the radius or searching a broader area.',
    enterAreaToSearch: 'Type your area name above and click Find, then hit Search.',
    pleaseSetLocation: 'Please enter and find an area first.',
  },
  hi: {
    home: 'होम',
    services: 'सेवाएं',
    search: 'खोज',
    dashboard: 'डैशबोर्ड',
    chats: 'चैट',
    login: 'लॉगिन',
    register: 'रजिस्टर',
    logout: 'लॉगआउट',
    findService: 'सेवा खोजें',
    searchPlaceholder: 'सेवाएं खोजें...',
    heroTitle: 'विश्वसनीय स्थानीय पेशेवर खोजें',
    heroSubtitle: 'अपने नज़दीक कुशल सेवा प्रदाताओं से जुड़ें — तेज़, विश्वसनीय और सत्यापित।',
    bookNow: 'अभी बुक करें',
    viewAll: 'सभी सेवाएं देखें',
    featuredServices: 'विशेष सेवाएं',
    howItWorks: 'यह कैसे काम करता है',
    topProviders: 'शीर्ष प्रदाता',
    allCategories: 'सभी श्रेणियां',
    findServices: '🔍 सेवाएं खोजें',
    browseCategory: 'श्रेणी के अनुसार ब्राउज़ करें',
    browseCategorySub: 'हमारी विस्तृत पेशेवर सेवाओं में से चुनें',
    howItWorksSteps: '3 आसान चरणों में शुरू करें',
    ctaTitle: 'क्या आप एक सेवा पेशेवर हैं?',
    ctaSubtitle: 'हमारे मार्केटप्लेस से जुड़ें और अपने क्षेत्र के ग्राहकों से जुड़ना शुरू करें।',
    registerAsWorker: 'कार्यकर्ता के रूप में रजिस्टर करें →',
    searchByLocation: 'स्थान के आधार पर खोजें',
    findNearYou: 'अपने नज़दीक सेवा प्रदाता खोजें',
    category: 'श्रेणी',
    radius: 'दायरा (कि.मी.)',
    keyword: 'कीवर्ड',
    keywordPlaceholder: 'जैसे: AC मरम्मत',
    searching: 'खोज रहे हैं...',
    myLocation: '📍 मेरा स्थान',
    viewDetails: 'विवरण देखें',
    noResults: 'नज़दीक कोई सेवा नहीं मिली। खोज दायरा बढ़ाएं।',
    servicesCount: 'सेवाएं',
    stepSearchTitle: 'खोज',
    stepSearchDesc: 'सही पेशेवर खोजने के लिए श्रेणियाँ ब्राउज़ करें या स्थान से खोजें।',
    stepConnectTitle: 'जुड़ें',
    stepConnectDesc: 'सेवा प्रदाताओं के साथ सीधे चैट करें और अपनी ज़रूरतें बताएं।',
    stepReviewTitle: 'समीक्षा',
    stepReviewDesc: 'काम पूरा होने के बाद समीक्षा छोड़ें और समुदाय की मदद करें।',
    priceOnRequest: 'कीमत पर अनुरोध',
    noServicesFound: 'कोई सेवा नहीं मिली। जल्द वापस आएं!',
    previous: 'पिछला',
    next: 'अगला',
    locationInfoMsg: 'सेवाएं कार्यकर्ता की प्रोफ़ाइल लोकेशन से जुड़ी होती हैं। कार्यकर्ता अपनी प्रोफ़ाइल में क्षेत्र सेट करते हैं — खोज आपके चुने क्षेत्र के पास मौजूद कार्यकर्ताओं की सेवाएं दिखाती है।',
    searchArea: 'आपका क्षेत्र / स्थान',
    areaPlaceholder: 'जैसे: अहमदाबाद, वस्त्रपुर, मणिनगर...',
    findArea: 'खोजें',
    usingLocation: 'खोज क्षेत्र',
    areaHidden: 'स्थान साझा नहीं',
    resultsNear: 'परिणाम नज़दीक',
    noResultsTip: 'दायरा बढ़ाएं या अलग क्षेत्र में खोजें।',
    enterAreaToSearch: 'ऊपर अपना क्षेत्र का नाम लिखें और खोजें पर क्लिक करें।',
    pleaseSetLocation: 'पहले कोई क्षेत्र खोजें।',
  },
  gu: {
    home: 'હોમ',
    services: 'સેવાઓ',
    search: 'શોધ',
    dashboard: 'ડૅશબોર્ડ',
    chats: 'ચૅટ',
    login: 'લૉગિન',
    register: 'નોંધણી',
    logout: 'લૉગઆઉટ',
    findService: 'સેવા શોધો',
    searchPlaceholder: 'સેવાઓ શોધો...',
    heroTitle: 'વિશ્વસનીય સ્થાનિક વ્યાવસાયિકો શોધો',
    heroSubtitle: 'તમારી નજીક કૌશલ્યવાન સેવા પ્રદાતાઓ સાથે જોડાઓ — ઝડપી, વિશ્વસનીય અને ચકાસાયેલ.',
    bookNow: 'હવે બૂક કરો',
    viewAll: 'બધી સેવાઓ જુઓ',
    featuredServices: 'વિશેષ સેવાઓ',
    howItWorks: 'તે કેવી રીતે કામ કરે છે',
    topProviders: 'ટોચના પ્રદાતાઓ',
    allCategories: 'બધી શ્રેણીઓ',
    findServices: '🔍 સેવાઓ શોધો',
    browseCategory: 'શ્રેણી દ્વારા બ્રાઉઝ કરો',
    browseCategorySub: 'અમારી વ્યાપક વ્યવસાયિક સેવાઓ માંથી પસંદ કરો',
    howItWorksSteps: '3 સરળ પગલામાં શરૂ કરો',
    ctaTitle: 'શું તમે સેવા વ્યાવસાયિક છો?',
    ctaSubtitle: 'અમારા માર્કેટપ્લેસ સાથે જોડાઓ અને તમારા વિસ્તારના ગ્રાહકો સાથે જોડાવા માંડો.',
    registerAsWorker: 'કામદાર તરીકે નોંધણી કરો →',
    searchByLocation: 'સ્થાન અનુસાર શોધ',
    findNearYou: 'તમારી નજીક સેવા વ્યાવસાયિકો શોધો',
    category: 'શ્રેણી',
    radius: 'ત્રિજ્યા (કિ.મી.)',
    keyword: 'કીવર્ડ',
    keywordPlaceholder: 'જેમ કે: AC રિપેર',
    searching: 'શોધ ચાલી રહી છે...',
    myLocation: '📍 મારું સ્થાન',
    viewDetails: 'વિગતો જુઓ',
    noResults: 'નજીકમાં કોઈ સેવા મળી નથી. શોધ ત્રિજ્યા વધારો.',
    servicesCount: 'સેવાઓ',
    stepSearchTitle: 'શોધ',
    stepSearchDesc: 'યોગ્ય વ્યાવસાયિક શોધવા માટે શ્રેણીઓ બ્રાઉઝ કરો અથવા સ્થાન દ્વારા શોધ કરો.',
    stepConnectTitle: 'જોડાઓ',
    stepConnectDesc: 'સેવા પ્રદાતાઓ સાથે સીધી ચૅટ કરો અને તમારી જરૂરિયાત સમજાવો.',
    stepReviewTitle: 'સમીક્ષા',
    stepReviewDesc: 'કામ પૂર્ણ થયા પછી સમીક્ષા લખો અને સમુદાયને મદદ કરો.',
    priceOnRequest: 'કિંમત વિનંતી પર',
    noServicesFound: 'કોઈ સેવા મળી નથી. ટૂંક સમયમાં ફરી તપાસો!',
    previous: 'પહેલાં',
    next: 'આગળ',
    locationInfoMsg: 'સેવાઓ કામદારની પ્રોફાઇલ સ્થાન સાથે જોડાયેલ છે. કામદારો પ્રોફાઇલ સેટ કરતી વખતે વિસ્તાર સેટ કરે છે — શોધ તમારા પસંદ કરેલ વિસ્તારની નજીકના કામદારોની સેવાઓ બતાવે છે.',
    searchArea: 'તમારો વિસ્તાર / સ્થાન',
    areaPlaceholder: 'દા.ત. અમદાવાદ, વસ્ત્રાપુર, મણિનગર...',
    findArea: 'શોધો',
    usingLocation: 'નજીક શોધ',
    areaHidden: 'સ્થાન શેર કર્યું નથી',
    resultsNear: 'પરિણામ નજીક',
    noResultsTip: 'ત્રિજ્યા વધારો અથવા અલગ વિસ્તારમાં શોધ કરો.',
    enterAreaToSearch: 'ઉપર તમારો વિસ્તાર લખો અને શોધો પર ક્લિક કરો.',
    pleaseSetLocation: 'પહેલાં કોઈ વિસ્તાર શોધો.',
  },
};

/* ── Composable ─────────────────────────────────────────── */
export const useLanguage = () => {
  const currentLang = useState<Lang>('app-lang', () => 'en');

  const setLang = (code: Lang) => {
    if (import.meta.server) return;
    currentLang.value = code;
    localStorage.setItem('lang', code);
    document.documentElement.lang = code === 'en' ? 'en' : code === 'hi' ? 'hi-IN' : 'gu-IN';
  };

  const initLang = () => {
    if (import.meta.server) return;
    const saved = localStorage.getItem('lang') as Lang | null;
    const lang: Lang = saved && ['en', 'hi', 'gu'].includes(saved) ? saved : 'en';
    currentLang.value = lang;
    document.documentElement.lang = lang === 'en' ? 'en' : lang === 'hi' ? 'hi-IN' : 'gu-IN';
  };

  const t = (key: string): string => {
    return translations[currentLang.value]?.[key] ?? translations['en'][key] ?? key;
  };

  const currentLangOption = computed(() =>
    LANGUAGES.find(l => l.code === currentLang.value) ?? LANGUAGES[0]
  );

  return { currentLang, currentLangOption, setLang, initLang, t, LANGUAGES };
};
