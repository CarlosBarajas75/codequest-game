import React, { useState, useRef } from 'react';
import fondo from '../assets/fondo.png';
import logo from '../assets/logocodequest.png';
import PhaserParticles from '../components/PhaserParticles';

const Register = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [password_confirmation, setPasswordConfirmation] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [inputsActive, setInputsActive] = useState(false);

  // Referencias para los inputs
  const nameRef = useRef<HTMLInputElement>(null);
  const emailRef = useRef<HTMLInputElement>(null);
  const passwordRef = useRef<HTMLInputElement>(null);
  const passwordConfRef = useRef<HTMLInputElement>(null);
  const inputRefs = useRef([
    nameRef,
    emailRef,
    passwordRef,
    passwordConfRef
  ]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    if (password !== password_confirmation) {
      setError('Las contraseñas no coinciden');
      return;
    }
    try {
      const response = await fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password, password_confirmation })
      });
      const data = await response.json();
      if (response.ok && data.token) {
        localStorage.setItem('token', data.token);
        setSuccess('Registro exitoso. Redirigiendo...');
        setTimeout(() => { window.location.href = '/'; }, 1500);
      } else {
        setError(data.message || 'Error al registrar');
      }
    } catch (err) {
      setError('Error de conexión');
    }
  };

  return (
    <div
      className="min-h-screen bg-cover bg-center flex flex-col items-center justify-center"
      style={{ backgroundImage: `url(${fondo})` }}
    >
      <PhaserParticles active={inputsActive} />
      <div className="register-content bg-gradient-to-br from-white/10 to-white/30 backdrop-blur-xl border-2 border-yellow-200/40 shadow-2xl flex flex-col items-center w-full h-screen min-h-screen justify-center rounded-none">
        <img src={logo} alt="Logo CodeQuest" className="register-logo mb-6 drop-shadow-lg animate-logo-magic" />
        <h2 className="text-2xl font-bold mb-6 text-center text-yellow-900 drop-shadow">Registro</h2>
        {error && <div className="mb-4 text-red-500">{error}</div>}
        {success && <div className="mb-4 text-green-600">{success}</div>}
        <form onSubmit={handleSubmit} className="w-full flex flex-col gap-4 max-w-lg mx-auto">
          <input
            ref={nameRef}
            type="text"
            placeholder="Nombre"
            value={name}
            onFocus={() => setInputsActive(true)}
            onBlur={() => setInputsActive(false)}
            onChange={e => setName(e.target.value)}
            className="w-full p-2 border-2 border-yellow-200/60 rounded-lg bg-white/20 text-yellow-900 placeholder-yellow-900/60 shadow-inner focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
            required
          />
          <input
            ref={emailRef}
            type="email"
            placeholder="Correo electrónico"
            value={email}
            onFocus={() => setInputsActive(true)}
            onBlur={() => setInputsActive(false)}
            onChange={e => setEmail(e.target.value)}
            className="w-full p-2 border-2 border-yellow-200/60 rounded-lg bg-white/20 text-yellow-900 placeholder-yellow-900/60 shadow-inner focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
            required
          />
          <input
            ref={passwordRef}
            type="password"
            placeholder="Contraseña"
            value={password}
            onFocus={() => setInputsActive(true)}
            onBlur={() => setInputsActive(false)}
            onChange={e => setPassword(e.target.value)}
            className="w-full p-2 border-2 border-yellow-200/60 rounded-lg bg-white/20 text-yellow-900 placeholder-yellow-900/60 shadow-inner focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
            required
          />
          <input
            ref={passwordConfRef}
            type="password"
            placeholder="Confirmar contraseña"
            value={password_confirmation}
            onFocus={() => setInputsActive(true)}
            onBlur={() => setInputsActive(false)}
            onChange={e => setPasswordConfirmation(e.target.value)}
            className="w-full p-2 border-2 border-yellow-200/60 rounded-lg bg-white/20 text-yellow-900 placeholder-yellow-900/60 shadow-inner focus:ring-2 focus:ring-yellow-400 focus:outline-none transition"
            required
          />
          <button type="submit" className="w-full bg-gradient-to-r from-yellow-700 via-yellow-600 to-yellow-800 text-white p-2 rounded-lg font-bold shadow-lg hover:scale-105 hover:from-yellow-600 hover:to-yellow-900 transition">Registrarse</button>
        </form>
      </div>
    </div>
  );
};

export default Register;
