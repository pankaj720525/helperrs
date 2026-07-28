/**
 * Location management composable with localStorage persistence.
 * Default location set to Ahmedabad, Gujarat (380001).
 */

export interface LocationState {
  city: string;
  pincode: string;
  area: string;
  state: string;
  lat: number;
  lng: number;
  formatted: string;
}

export interface CityOption {
  name: string;
  state: string;
  pincode: string;
  lat: number;
  lng: number;
  popularAreas: string[];
}

export const POPULAR_CITIES: CityOption[] = [
  {
    name: 'Ahmedabad',
    state: 'Gujarat',
    pincode: '380001',
    lat: 23.0225,
    lng: 72.5714,
    popularAreas: ['Navrangpura', 'Vastrapur', 'Satellite', 'Bodakdev', 'SG Highway', 'Maninagar', 'Bopal', 'Chandkheda', 'Paldi', 'CG Road']
  },
  {
    name: 'Surat',
    state: 'Gujarat',
    pincode: '395001',
    lat: 21.1702,
    lng: 72.8311,
    popularAreas: ['Adajan', 'Vesu', 'Varachha', 'Piplod', 'Katargam']
  },
  {
    name: 'Vadodara',
    state: 'Gujarat',
    pincode: '390001',
    lat: 22.3072,
    lng: 73.1812,
    popularAreas: ['Alkapuri', 'Gotri', 'Manjalpur', 'Sayajigunj', 'Karelibaug']
  },
  {
    name: 'Mumbai',
    state: 'Maharashtra',
    pincode: '400001',
    lat: 19.0760,
    lng: 72.8777,
    popularAreas: ['Andheri', 'Bandra', 'Powai', 'Borivali', 'Juhu']
  },
  {
    name: 'Delhi NCR',
    state: 'Delhi',
    pincode: '110001',
    lat: 28.6139,
    lng: 77.2090,
    popularAreas: ['Connaught Place', 'South Ext', 'Dwarka', 'Gurugram', 'Noida']
  },
  {
    name: 'Bengaluru',
    state: 'Karnataka',
    pincode: '560001',
    lat: 12.9716,
    lng: 77.5946,
    popularAreas: ['Indiranagar', 'Koramangala', 'HSR Layout', 'Whitefield', 'JP Nagar']
  },
  {
    name: 'Pune',
    state: 'Maharashtra',
    pincode: '411001',
    lat: 18.5204,
    lng: 73.8567,
    popularAreas: ['Kothrud', 'Viman Nagar', 'Baner', 'Hinjawadi', 'Aundh']
  },
  {
    name: 'Hyderabad',
    state: 'Telangana',
    pincode: '500001',
    lat: 17.3850,
    lng: 78.4867,
    popularAreas: ['Banjara Hills', 'Gachibowli', 'HITECH City', 'Jubilee Hills', 'Madhapur']
  }
];

const DEFAULT_LOCATION: LocationState = {
  city: 'Ahmedabad',
  pincode: '380001',
  area: 'Vastrapur',
  state: 'Gujarat',
  lat: 23.0225,
  lng: 72.5714,
  formatted: 'Vastrapur, Ahmedabad 380001'
};

export const useUserLocation = () => {
  const currentLocation = useState<LocationState>('user-location', () => DEFAULT_LOCATION);
  const isModalOpen = useState<boolean>('location-modal-open', () => false);

  const initLocation = () => {
    if (import.meta.server) return;
    try {
      const saved = localStorage.getItem('user_location');
      if (saved) {
        currentLocation.value = JSON.parse(saved);
      }
    } catch {
      currentLocation.value = DEFAULT_LOCATION;
    }
  };

  const setLocation = (newLoc: Partial<LocationState>) => {
    currentLocation.value = {
      ...currentLocation.value,
      ...newLoc,
      formatted: `${newLoc.area || currentLocation.value.area}, ${newLoc.city || currentLocation.value.city} ${newLoc.pincode || currentLocation.value.pincode}`
    };
    if (import.meta.client) {
      localStorage.setItem('user_location', JSON.stringify(currentLocation.value));
    }
  };

  const selectCity = (cityName: string, areaName?: string) => {
    const city = POPULAR_CITIES.find(c => c.name.toLowerCase() === cityName.toLowerCase());
    if (city) {
      setLocation({
        city: city.name,
        state: city.state,
        pincode: city.pincode,
        area: areaName || city.popularAreas[0] || city.name,
        lat: city.lat,
        lng: city.lng
      });
    } else {
      setLocation({
        city: cityName,
        area: areaName || cityName,
        pincode: '380001'
      });
    }
  };

  const openModal = () => { isModalOpen.value = true; };
  const closeModal = () => { isModalOpen.value = false; };

  return {
    currentLocation,
    isModalOpen,
    POPULAR_CITIES,
    initLocation,
    setLocation,
    selectCity,
    openModal,
    closeModal
  };
};
