import { useSelector } from 'react-redux'
import { Link } from 'react-router-dom'
import { Shield, Search, AlertTriangle, CheckCircle } from 'lucide-react'

export default function DashboardPage() {
  const { user } = useSelector(s => s.auth)

  const tierColors = {
    guest: 'text-gray-400', verified: 'text-blue-400',
    kyc_lite: 'text-yellow-400', kyc3: 'text-green-400',
  }

  const tierLabels = {
    guest: 'Guest', verified: 'Phone Verified',
    kyc_lite: 'KYC Lite', kyc3: 'Full KYC ✓',
  }

  return (
    <div className="min-h-screen bg-gray-950 text-white p-6">
      <div className="max-w-4xl mx-auto">
        <div className="flex items-center justify-between mb-8">
          <h1 className="text-2xl font-bold">MyDigitalShadow</h1>
          <div className="flex items-center gap-2">
            <Shield className="w-5 h-5" />
            <span className={`font-semibold ${tierColors[user?.trust_tier || 'guest']}`}>
              {tierLabels[user?.trust_tier || 'guest']}
            </span>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
          <div className="bg-gray-900 rounded-xl p-5">
            <p className="text-gray-400 text-sm">Credits</p>
            <p className="text-3xl font-bold text-blue-400">{user?.credits || 0}</p>
          </div>
          <div className="bg-gray-900 rounded-xl p-5">
            <p className="text-gray-400 text-sm">Trust Tier</p>
            <p className={`text-xl font-semibold ${tierColors[user?.trust_tier || 'guest']}`}>
              {tierLabels[user?.trust_tier || 'guest']}
            </p>
          </div>
          <div className="bg-gray-900 rounded-xl p-5">
            <p className="text-gray-400 text-sm">Total Scans</p>
            <p className="text-3xl font-bold text-purple-400">0</p>
          </div>
        </div>

        {user?.trust_tier === 'guest' && (
          <div className="bg-yellow-900/30 border border-yellow-600 rounded-xl p-4 mb-6 flex items-start gap-3">
            <AlertTriangle className="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" />
            <div>
              <p className="text-yellow-300 font-semibold">Complete KYC to unlock all scans</p>
              <p className="text-yellow-400/70 text-sm mt-1">Verify your identity to access breach checking, name scans, and more.</p>
              <Link to="/kyc" className="inline-block mt-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm px-4 py-1.5 rounded-lg transition">
                Start KYC →
              </Link>
            </div>
          </div>
        )}

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {[
            { icon: Search, title: 'Breach Check', desc: 'Check if your email/phone is in leaked databases', href: '/scan', tier: 'verified' },
            { icon: Shield, title: 'Name Deep-Dive', desc: 'Scan your name across govt portals and court records', href: '/scan', tier: 'kyc3' },
          ].map(card => (
            <Link key={card.title} to={card.href} className="bg-gray-900 hover:bg-gray-800 rounded-xl p-5 transition border border-gray-800 hover:border-gray-600">
              <card.icon className="w-6 h-6 text-blue-400 mb-3" />
              <h3 className="font-semibold text-white">{card.title}</h3>
              <p className="text-gray-400 text-sm mt-1">{card.desc}</p>
              <span className="inline-block mt-2 text-xs bg-gray-800 text-gray-300 px-2 py-0.5 rounded">Requires: {card.tier}</span>
            </Link>
          ))}
        </div>
      </div>
    </div>
  )
}
