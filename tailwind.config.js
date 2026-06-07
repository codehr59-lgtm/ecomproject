/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#f48721',
        'primary-alt': '#ff9800',
        dark: '#041f1e',
        ink: '#222831',
        cream: '#fbf9f5',
        text: '#666666',
        'text-mute': '#5c3d1e',
        strike: '#aaaaaa',
        border: '#cccccc',
        'border-light': '#eeeeee',
        success: '#34be82',
        whatsapp: '#1daa61',
        call: '#1e3a8a',
        sale: '#ff1818',
        ink404: '#252a34',
      },
      borderRadius: { sm: '4px', DEFAULT: '6px', lg: '8px' },
      fontFamily: { base: ['"Open Sans"', 'sans-serif'] },
      boxShadow: { card: '0 4px 12px rgba(0,0,0,0.08)' },
      height: { input: '47px' },
      maxWidth: { content: '1400px' },
    },
  },
  plugins: [],
}
