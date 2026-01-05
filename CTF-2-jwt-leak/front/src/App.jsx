import React, { useState } from "react";
import axios from "axios";
import "./App.css";
import profileImage from "./Tesla_Sarony.jpg";


export default function App() {
  const [file, setFile] = useState("");
  const [response, setResponse] = useState("");
  const [token, setToken] = useState("");
  const [adminResponse, setAdminResponse] = useState("");
  const isMaintenance = window.location.pathname.startsWith("/maintenance");

  const handleDownload = async () => {
    try {
      const res = await axios.get('/api/download', {
        params: { file },
        responseType: 'text',
        transformResponse: [(data) => data], // pas de parsing JSON
        headers: { Accept: 'text/plain,*/*' },
      });
      setResponse(res.data);
    } catch (err) {
      setResponse("Request rejected or file not found.");
    }
  };

  const handleAdminRequest = async () => {
    try {
      const headers = token ? { Authorization: `Bearer ${token}` } : {};
      const { data } = await axios.get('/api/admin', { headers });
      setAdminResponse(JSON.stringify(data, null, 2));
    } catch (err) {
      const fallback =
        err.response?.data !== undefined
          ? JSON.stringify(err.response.data, null, 2)
          : "Request failed.";
      setAdminResponse(fallback);
    }
  };

  if (isMaintenance) {
    return (
      <div className="app maintenance-app">
        <div className="maintenance-panel">
          <div className="maintenance-header">
            <img className="maintenance-avatar" src={profileImage} alt="Nikola Tesla" />
            <div className="maintenance-copy">
              <span className="maintenance-badge">Executive Uplink</span>
              <h1>Tesla Skyforge — Admin Relay Intake</h1>
              <p>
                Submit a Forge-Token signed with Tesla&apos;s legacy key to query the Skyline Sentinels
                dispatch queue. The relay still trusts any token bearing his signature.
              </p>
            </div>
          </div>
          <input
            id="forge-token"
            type="text"
            placeholder="..."
            value={token}
            onChange={(event) => {
              setToken(event.target.value);
              setAdminResponse("");
            }}
            className="maintenance-input"
            autoComplete="off"
          />
          <button type="button" className="maintenance-button" onClick={handleAdminRequest}>
            What does this button do? 
          </button>
          <pre className="maintenance-output">
            {adminResponse}
          </pre>
        </div>
      </div>
    );
  }

  return (
    <div className="app">
      <div className="layout">
        <header className="hero">
          <div className="hero__media">
            <img className="hero__portrait" src={profileImage} alt="Nikola Tesla" />
            <span className="hero__badge">Skyforge Archives</span>
          </div>
          <div className="hero__content">
            <h1>“Tesla Skyforge” — command console for the night crew</h1>
            <p>
              Nikola Tesla left this maintenance terminal running so contractors could retrieve
              diagnostic coils. Rumor has it the admin relay still trusts any request signed with his
              old clearance cipher. Role admin is the key.
            </p>
            <p>
              Tesla often cached schematics and tokens wherever inspiration struck. Reconstruct his path, then forge the signature that opens the Skyline Sentinels relay.
            </p>
          </div>
        </header>

        <main className="grid">
          <section className="card card--primary">
            <div className="card__header">
              <h2>Operational log bridge</h2>
              <p>
                Enter the relative reference of the artifact you need. Technicians usually request
                nightly extracts such as <code>logs/maintenance/cycle-042.txt</code>.
              </p>
            </div>

            <label className="input-group">
              <span>Log reference</span>
              <input
                type="text"
                placeholder="logs/maintenance/cycle-042.txt"
                value={file}
                onChange={(event) => setFile(event.target.value)}
              />
            </label>

            <button type="button" className="cta" onClick={handleDownload}>
              Retrieve artifact
            </button>

            <div className="console">
              <span className="console__label">Console output</span>
              <pre>{response || "Awaiting retrieval request..."}</pre>
            </div>
          </section>

          <aside className="card card--secondary">
            <h2>Executive uplink</h2>
            <p>
              Skyline Sentinels accept a JWT in the <code>Forge-Token</code> header. Tesla’s drafts may
              still expose the signing secret if you trawl the archives deeply enough.
            </p>
            <div className="hint">
              <h3>Archivist’s note</h3>
              <p>
                You should keep your eyes peeled everywhere to be able to use this famous key. Don't hesitate to go back to the source, hmmm, interesting.
              </p>
            </div>
          </aside>
        </main>
      </div>
    </div>
  );
}
