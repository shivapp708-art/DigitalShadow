import { useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { useNavigate } from 'react-router-dom'
import { sendOtp, verifyOtp } from '../store/authSlice'
import toast from 'react-hot-toast'

export default function LoginPage() {
  const dispatch = useDispatch()
  const navigate = useNavigate()
  const { loading } = useSelector(s => s.auth)

  const [step, setStep] = useState('input')  // 'input' | 'otp'
  const [identifier, setIdentifier] = useState('')
  const [otp, setOtp] = useState('')

  const handleSend = async (e) => {
    e.preventDefault()
    const isPhone = /^[6-9]\d{9}$/.test(identifier)
    const isEmail = /^[^@]+@[^@]+\.[^@]+$/.test(identifier)
    if (!isPhone && !isEmail) return toast.error('Enter valid Indian mobile number or email')

    const res = await dispatch(sendOtp({ identifier, type: isPhone ? 'phone' : 'email' }))
    if (res.meta.requestStatus === 'fulfilled') {
      toast.success('OTP sent!')
      setStep('otp')
    } else {
      toast.error(res.payload?.message || 'Failed to send OTP')
    }
  }

  const handleVerify = async (e) => {
    e.preventDefault()
    const res = await dispatch(verifyOtp({ identifier, otp }))
    if (res.meta.requestStatus === 'fulfilled') {
      toast.success('Welcome to MyDigitalShadow!')
      navigate('/')
    } else {
      toast.error(res.payload?.message || 'Invalid OTP')
    }
  }

  return (
    <div className="min-h-screen bg-gray-950 flex items-center justify-center p-4">
      <div className="w-full max-w-md bg-gray-900 rounded-2xl p-8 shadow-2xl">
        <div className="text-center mb-8">
          <h1 className="text-3xl font-bold text-white">MyDigitalShadow</h1>
          <p className="text-gray-400 mt-2">Know your digital footprint. Own your privacy.</p>
        </div>

        {step === 'input' ? (
          <form onSubmit={handleSend} className="space-y-4">
            <div>
              <label className="block text-sm text-gray-400 mb-1">Mobile Number or Email</label>
              <input
                type="text"
                value={identifier}
                onChange={e => setIdentifier(e.target.value)}
                placeholder="9876543210 or user@example.com"
                className="w-full bg-gray-800 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                autoComplete="tel"
              />
            </div>
            <button
              type="submit"
              disabled={loading}
              className="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold rounded-lg py-3 transition"
            >
              {loading ? 'Sending...' : 'Send OTP'}
            </button>
            <p className="text-xs text-gray-500 text-center">
              No passwords. No tracking. DPDP Act 2023 compliant.
            </p>
          </form>
        ) : (
          <form onSubmit={handleVerify} className="space-y-4">
            <p className="text-gray-300 text-sm">OTP sent to <strong>{identifier}</strong></p>
            <div>
              <label className="block text-sm text-gray-400 mb-1">Enter 6-digit OTP</label>
              <input
                type="text"
                value={otp}
                onChange={e => setOtp(e.target.value)}
                placeholder="123456"
                maxLength={6}
                className="w-full bg-gray-800 text-white rounded-lg px-4 py-3 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500"
                autoComplete="one-time-code"
              />
            </div>
            <button
              type="submit"
              disabled={loading || otp.length < 6}
              className="w-full bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-semibold rounded-lg py-3 transition"
            >
              {loading ? 'Verifying...' : 'Verify & Login'}
            </button>
            <button type="button" onClick={() => setStep('input')} className="w-full text-gray-400 text-sm">
              ← Change number/email
            </button>
          </form>
        )}
      </div>
    </div>
  )
}
